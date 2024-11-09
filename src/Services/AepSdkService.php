<?php

namespace Hiworld\CTWing\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AepSdkService
{
    protected $baseUrl;
    protected $timeUrl;
    protected $appKey;
    protected $appSecret;
    protected $masterKey;
    protected $offset = 0;
    protected $lastGetOffsetTime = 0;

    /**
     * Constructor to initialize the service with necessary parameters.
     *
     * @param string $baseUrl Base URL for the API
     * @param string $timeUrl URL for time synchronization
     * @param string $appKey Application Key
     * @param string $appSecret Application Secret
     * @param string $masterKey Master Key
     */
    /**
     * 初始化静态属性
     */
    public function __construct($baseUrl, $timeUrl, $appKey, $appSecret, $masterKey)
    {
        // 从配置中获取 baseUrl 和 timeUrl
        $this->baseUrl = $baseUrl;
        $this->timeUrl = $timeUrl;
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->masterKey = $masterKey;

        Log::info('我在这里：'.$this->masterKey);
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
    public function sendSdkRequest($path, $head, $param, $body, $version, $method = 'GET')
    {
        // 调试输出

        // 构建请求地址
        $url = $this->baseUrl . $path;
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
        $currentTime = $this->getMillisecond();
        if ($currentTime - $this->lastGetOffsetTime > 300 * 1000) {
            $this->offset = $this->getTimeOffset();
            $this->lastGetOffsetTime = $currentTime;
        }

        $timestamp = $this->getMillisecond() + $this->offset;
        // $body = json_encode($body);

        // 创建请求头，包含 app_key 和其他必要信息
        $head['MasterKey'] = $this->masterKey;
        $headers = [
            'application' => $this->appKey,
            'timestamp'   => (string) $timestamp,
            'version'     => $version,
            'signature'   => $this->sign(array_merge($param ?? [], $head ?? []), $timestamp, $body),
            // 'MasterKey'   => $this->masterKey ?? '',
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
            Log::error('HTTP Request Failed: ' . $response->body());
            throw new Exception('HTTP Request Failed: ' . $response->body());
        }

        return $response->body();
    }



    /**
     * 获取时间偏移量
     *
     * @return int
     */
    public function getTimeOffset()
    {
        $offsetTime = 0;

        try {
            $start = $this->getMillisecond();
            $response = Http::get($this->timeUrl);
            $end = $this->getMillisecond();

            if ($response->successful() && $response->header('x-ag-timestamp')) {
                $offsetTime = round($response->header('x-ag-timestamp') - ($start + $end) / 2);
            } else {
                throw new Exception("Error: cannot get timestamp.");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $offsetTime;
    }

    /**
     * 获取当前时间戳（毫秒）
     *
     * @return float
     */
    public function getMillisecond()
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
    public function sign($param, $timestamp, $body = null)
    {
        ksort($param);

        $temp = array_merge([
            'application' => $this->appKey,
            'timestamp'   => $timestamp
        ], $param);

        $s = '';
        foreach ($temp as $key => $value) {
            $s .= $key . ':' . $value . "\n";
        }

        if ($body) {
            $s .= json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
        }

        $hashHmac = hash_hmac('sha1', $s, $this->appSecret, true);
        return base64_encode($hashHmac);
    }
}
