<?php

namespace Hiworld\CTWing;
use GuzzleHttp\Client;
use Hiworld\CTWing\Services\AepSdkCore;
use Illuminate\Support\Facades\Http;

class AepTool
{
    protected $client;
    protected $baseUrl;
    protected $timeUrl;
    protected $appKey;
    protected $appSecret;
    protected $masterKey;

    public function __construct($baseUrl, $timeUrl, $appKey, $appSecret, $masterKey)
    {
        $this->baseUrl = $baseUrl;
        $this->timeUrl = $timeUrl;
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->masterKey = $masterKey;
        $this->client = new Client();
    }

    public function sendRequest($path, $head, $param, $body, $version, $method = 'GET')
    {
        $url = $this->baseUrl . $path;
        $timestamp = (string)(AepSdkCore::getMillisecond() + AepSdkCore::$offset);

        $string = json_encode($body);
        // 生成签名
        $signature = AepSdkCore::sign($param ?? [], $timestamp, $this->appKey, $this->appSecret, AepSdkCore::getBytes($string));
        // $signature = $this->sign(array_merge($param ?? [], $head ?? []), $timestamp, $body);

        // 设置请求选项
        $options = [
            'headers' => array_merge($head ?? [], [
                'application' => $this->appKey,
                'timestamp' => $timestamp,
                'version' => $version,
                'signature' => $signature,
                'MasterKey' => $this->masterKey,
                'Content-Type' => 'application/json',
            ]),
        ];

        // if ($method === 'POST') {
        //     $options['json'] = $body; // 使用 JSON 格式发送请求体
        // }

        // $response = $this->client->request($method, $url, $options);
        $response = Http::withHeaders($options['headers'])
            ->timeout(80)
            ->connectTimeout(60)
            ->post($url, $body);
        // return $response->getBody()->getContents(); // 获取响应内容

        dd($response->getBody()->getContents());
    }
}
