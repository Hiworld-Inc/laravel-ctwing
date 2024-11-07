<?php

namespace Hiworld\CTWing\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AepSdkService
{
    // 声明静态属性
    protected $baseUrl;
    protected $timeUrl;
    protected $appKey;
    protected $appSecret;
    protected static $offset = 0;
    protected static $lastGetOffsetTime = 0;

    public function __construct()
    {
        $config = config('ctwing');
        $this->baseUrl = $config['base_url'];
        $this->timeUrl = $config['time_url'];
        $this->appKey = $config['app_key'];
        $this->appSecret = $config['app_secret'];
    }

    /**
     * 发送 SDK 请求
     *
     * @param string $path 请求路径
     * @param array|null $head 请求头部
     * @param array|null $param 请求参数
     * @param string|null $body 请求 body
     * @param string $version API 版本号
     * @param string $method 请求方法
     * @return string|bool 返回响应
     * @throws Exception
     */
    public static function sendSdkRequest($path, $head, $param, $body, $version, $method = 'GET')
    {
        // 调试输出
        // Log::info('HTTP Method: ' . $method);

        // 确保静态属性已经初始化
        if (!isset(self::$baseUrl) || !isset(self::$timeUrl)) {
            self::init();
        }

        // 构建请求地址
        $url = self::$baseUrl . $path;
        $urlParams = [];

        if (is_array($param)) {
            foreach ($param as $key => $value) {
                $urlParams[] = $key . '=' . $value;
            }
        }

        if (count($urlParams) > 0) {
            $url = $url . '?' . implode('&', $urlParams);
        }

        // 获取当前时间戳和偏移量
        $currentTime = self::getMillisecond();
        if ($currentTime - self::$lastGetOffsetTime > 300 * 1000) {
            self::$offset = self::getTimeOffset();
            self::$lastGetOffsetTime = $currentTime;
        }

        $timestamp = self::getMillisecond() + self::$offset;

        // 创建请求头，包含 app_key 和其他必要信息
        $headers = [
            'application' => self::$appKey,
            'timestamp'   => (string) $timestamp,
            'version'     => $version,
            'signature'   => self::sign(array_merge($param ?? [], $head ?? []), $timestamp, $body),
            'MasterKey'   => $head['MasterKey'] ?? '',
            'Content-Type' => 'application/json'
        ];

        if ($head != null) {
            $headers = array_merge($headers, $head);
        }

        // 使用 switch-case 来确保 HTTP 请求方法是合法的
        switch (strtolower($method)) {
            case 'get':
                $response = Http::withHeaders($headers)
                    ->timeout(80)
                    ->connectTimeout(60)
                    ->get($url);
                break;
            case 'post':
                $response = Http::withHeaders($headers)
                    ->timeout(80)
                    ->connectTimeout(60)
                    ->post($url, $body);
                break;
            case 'put':
                $response = Http::withHeaders($headers)
                    ->timeout(80)
                    ->connectTimeout(60)
                    ->put($url, $body);
                break;
            case 'delete':
                $response = Http::withHeaders($headers)
                    ->timeout(80)
                    ->connectTimeout(60)
                    ->delete($url, $body);
                break;
            default:
                throw new Exception("Invalid HTTP method: $method");
        }

        if ($response->failed()) {
            // Log::error('HTTP Request Failed: ' . $response->body());
            throw new Exception('HTTP Request Failed: ' . $response->body());
        }

        return json_decode($response->body(), true);
    }



    /**
     * 获取时间偏移量
     *
     * @return int
     */
    protected static function getTimeOffset()
    {
        $offsetTime = 0;

        try {
            $start = self::getMillisecond();
            $response = Http::get(self::$timeUrl);
            $end = self::getMillisecond();

            if ($response->successful() && $response->header('x-ag-timestamp')) {
                $offsetTime = round($response->header('x-ag-timestamp') - ($start + $end) / 2);
            } else {
                throw new Exception("Error: cannot get timestamp.");
            }
        } catch (Exception $e) {
            // Log::error($e->getMessage());
        }

        return $offsetTime;
    }

    /**
     * 获取当前时间戳（毫秒）
     *
     * @return float
     */
    protected static function getMillisecond()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * 计算签名
     *
     * @param array $param 请求参数
     * @param string $timestamp 时间戳
     * @param string|null $body 请求body
     * @return string 签名数据
     */
    protected static function sign($param, $timestamp, $body = null)
    {
        ksort($param);

        // 将 application 和 timestamp 合并进请求参数中
        $temp = array_merge([
            'application' => self::$appKey,  // appKey 应该作为 application
            'timestamp'   => $timestamp
        ], $param);

        $s = '';
        foreach ($temp as $key => $value) {
            $s .= $key . ':' . $value . "\n";  // 确保每个键值对以 'key:value' 格式拼接
        }

        // 检查是否应该包含 body，并确保格式一致
        if ($body) {
            $s .= json_encode($body, JSON_UNESCAPED_UNICODE) . "\n";  // 确保 body 使用正确的 JSON 格式
        }

        // 生成 HMAC-SHA1 签名
        $hashHmac = hash_hmac('sha1', $s, self::$appSecret, true);  // 使用 appSecret 作为密钥
        return base64_encode($hashHmac);  // base64 编码签名
    }
}
