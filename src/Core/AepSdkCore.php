<?php


namespace Hiworld\CTWing\Core;

use Exception;

class AepSdkCore
{
    public static $baseUrl="https://2000514878.api.ctwing.cn";
    public static $timeUrl ="https://2000514878.api.ctwing.cn/echo";
    static $offset=0;
    static $lastGetOffsetTime=0;

    /***
     * @param $path  请求地址，例如：'/aep_product_management/product'
     * @param $head  请求头部公共参数：array or null
     * @param $param 请求参数：array("productId"=>"9392") or null
     * @param $body 请求BODY：null or string
     * @param $version API版本号：'20181031202055'
     * @param $application 应用的AppKey，如果需要进行签名认证则需要填写，例如：'91Ebv1S0HBb'
     * @param $secret 密钥，例如："FJDq8agNp5"
     * @param $method 请求方法："GET" "PUT" "POST" "DELETE"
     * @return 返回响应：bool or string
     */
    public static function sendSDkRequest($path, $head, $param, $body, $version, $application, $secret, $method="GET"){

        $ch = curl_init();
        //获取请求地址
        $url=self::$baseUrl.$path;
        $urlparams=array();
        if(is_array($param)) {
            foreach ($param as $key => $value) {
                array_push($urlparams, $key . '=' . $value);
            }
        }
        if(count($urlparams)>0) {
            $url = $url . '?' . implode('&', $urlparams);
        }
        print_r("url: ".$url."\n");

        if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        if($method=="POST"){
            curl_setopt($ch, CURLOPT_POST, true);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        //连接主机时的最长等待时间:60second
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        //整个cURL函数执行过程的最长等待时间:80second
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);

        //获取api配置参数
        $paramTmp=array();
        if($head!=null){
            $paramTmp=array_merge($head,$paramTmp);
        }
        if($param!=null){
            $paramTmp=array_merge($paramTmp,$param);
        }
        //获取时间戳
        $curentTime=self::getMillisecond();
        //300秒调用一次
        if($curentTime-self::$lastGetOffsetTime>300*1000) {
            self::$offset=self::getTimeOffset();
            self::$lastGetOffsetTime=$curentTime;
        }

        $timestamp=self::getMillisecond()+self::$offset;
        //将签名数据填入请求头部
        $header=array("application"=>$application,"timestamp"=>"".$timestamp,"version"=>$version,"signature"=>self::sign($paramTmp,$timestamp,$application,$secret,self::getBytes($body)));
        if($head!=null){
            $header=array_merge($header,$head);
        }
        if (is_array($header) && 0 < count($header)) {
            $httpHeaders = self::getHttpHearders($header);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $response= curl_exec($ch);
        if (curl_errno($ch)) {
            die('Curl error: Errno: '. curl_errno($ch)." ".curl_error($ch));
        }
        curl_close($ch);

        return $response;
    }

    static function getHttpHearders($headers)
    {
        $httpHeader = array();
        foreach ($headers as $key => $value)
        {
            $httpHeader[]=$key.":".$value;
        }
        return $httpHeader;
    }

    /***
     * 获取时间偏移量
     * @return false|float|int
     */
    static function getTimeOffset(){
        $offsettime=0;
        $url=self::$timeUrl;
        try{
            $start=self::getMillisecond();
            //校验能否获取响应状态
            stream_context_set_default(array(
                'ssl' => array(
                    'verify_host' => false,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),
            ));
            $response=get_headers($url,1);
            $end=self::getMillisecond();
            $head=$response["x-ag-timestamp"];
            if ($head!=null){
                $offsettime =round( ($head - ($start + $end) / 2));
            }
            else {
                throw new Exception("Error：cannot get timestamp.");
            }
        }
        catch (Exception $exception) {
            print_r($exception->getMessage() . "\n");
        }
        return $offsettime;
    }

    /***
     * 获取系统当前时间
     * @return float
     */
    static function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    static function getBytes($str)
    {
        $bytes = array();
        for ($pos = 0; $pos < strlen($str); $pos++) {
            $byte = substr($str, $pos);
            $bytes[] = ord($byte);
        }
        return $bytes;
    }

    static function toStr($bytes)
    {
        $str = '';
        foreach ($bytes as $ch) {
            $str .= chr($ch);
        }
        return $str;
    }

    /***
     * 计算签名
     * 将业务数据连同timestamp、application一起签名后的数据，如果需要进行签名认证则需要填写
     * @param $param   api配置参数表
     * @param $timestamp UNIX格式时间戳
     * @param $application appKey,到应用管理打开应用可以找到此值
     * @param $secret 密钥,到应用管理打开应用可以找到此值
     * @param $body 请求body数据,如果是GET请求，此值写null
     * @return 签名数据
     */
    static function sign($param,$timestamp,$application,$secret,$body)
    {
        //将业务数据排序
        ksort($param);

        //写入timestamp、application数据
        $temp = array("application" => $application, "timestamp" => $timestamp);
        $temp = $temp + $param;
        $s = "";
        foreach ($temp as $key => $value) {
            $string = ($key . ":" . "$value\n");
            $s = $s . $string;
        }

        $text = self::getBytes($s);
        // 将body数据写入需要签名的字符流中
        if ($body != null && count($body) > 0) {
            $text = array_merge($text, $body, self::getBytes("\n"));
        }
        //  得到需要签名的字符串
        $encryptText = self::toStr($text);
        print_r("Sign string: " . $encryptText);
        //hmac-sha1编码
        $result = self::HmacSHA1Encrypt($encryptText, $secret);

        return ($result);
    }

    /***
     * hmac-sha1编码
     */
    static function HmacSHA1Encrypt($encryptText, $encryptKey)
    {
        $hash_hmac = hash_hmac("sha1", $encryptText, $encryptKey, true);
        $signature = base64_encode($hash_hmac);

        return $signature;
    }
}
