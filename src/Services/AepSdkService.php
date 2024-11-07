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
    public function __construct($baseUrl, $timeUrl, $appKey, $appSecret, $masterKey)
    {
        $this->baseUrl = $baseUrl;
        $this->timeUrl = $timeUrl;
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->masterKey = $masterKey;
    }

    /**
     * Send an SDK request to the API.
     *
     * @param string $path Request path
     * @param array|null $param Request parameters
     * @param string|null $body Request body
     * @param string $version API version
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @return array|string|bool Response from the API
     * @throws Exception if the HTTP request fails
     */
    public function sendSdkRequest($path, $param, $body, $version, $method = 'GET')
    {
        $url = $this->buildUrl($path, $param);
        $timestamp = $this->getTimestamp();

        $headers = $this->buildHeaders($param, $timestamp, $body, $version);

        $response = $this->makeHttpRequest($url, $headers, $body, $method);

        if ($response->failed()) {
            throw new Exception('HTTP Request Failed: ' . $response->body());
        }

        return json_decode($response->body(), true);
    }

    /**
     * Build the full URL with parameters.
     *
     * @param string $path
     * @param array|null $param
     * @return string
     */
    protected function buildUrl($path, $param)
    {
        $url = $this->baseUrl . $path;
        $urlParams = [];

        if (is_array($param)) {
            foreach ($param as $key => $value) {
                $urlParams[] = $key . '=' . $value;
            }
        }

        if (count($urlParams) > 0) {
            $url .= '?' . implode('&', $urlParams);
        }

        return $url;
    }

    /**
     * Get the current timestamp with offset.
     *
     * @return float
     */
    protected function getTimestamp()
    {
        $currentTime = $this->getMillisecond();
        if ($currentTime - $this->lastGetOffsetTime > 300 * 1000) {
            $this->offset = $this->getTimeOffset();
            $this->lastGetOffsetTime = $currentTime;
        }

        return $this->getMillisecond() + $this->offset;
    }

    /**
     * Build headers for the HTTP request.
     *
     * @param array|null $param
     * @param string $timestamp
     * @param string|null $body
     * @param string $version
     * @return array
     */
    protected function buildHeaders($param, $timestamp, $body, $version)
    {
        return [
            'application' => $this->appKey,
            'timestamp'   => (string) $timestamp,
            'version'     => $version,
            'signature'   => $this->sign($param ?? [], $timestamp, $body),
            'MasterKey'   => $this->masterKey,
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Make the HTTP request based on the method.
     *
     * @param string $url
     * @param array $headers
     * @param string|null $body
     * @param string $method
     * @return \Illuminate\Http\Client\Response
     * @throws Exception
     */
    protected function makeHttpRequest($url, $headers, $body, $method)
    {
        switch (strtolower($method)) {
            case 'get':
                return Http::withHeaders($headers)->timeout(80)->connectTimeout(60)->get($url);
            case 'post':
                return Http::withHeaders($headers)->timeout(80)->connectTimeout(60)->post($url, $body);
            case 'put':
                return Http::withHeaders($headers)->timeout(80)->connectTimeout(60)->put($url, $body);
            case 'delete':
                return Http::withHeaders($headers)->timeout(80)->connectTimeout(60)->delete($url, $body);
            default:
                throw new Exception("Invalid HTTP method: $method");
        }
    }

    /**
     * Get the time offset from the server.
     *
     * @return int
     */
    protected function getTimeOffset()
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
     * Get the current timestamp in milliseconds.
     *
     * @return float
     */
    protected function getMillisecond()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * Calculate the signature for the request.
     *
     * @param array $param
     * @param string $timestamp
     * @param string|null $body
     * @return string
     */
    protected function sign($param, $timestamp, $body = null)
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
            $s .= json_encode($body, JSON_UNESCAPED_UNICODE) . "\n";
        }

        $hashHmac = hash_hmac('sha1', $s, $this->appSecret, true);
        return base64_encode($hashHmac);
    }
}
