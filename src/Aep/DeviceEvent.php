<?php
namespace Hiworld\CTWing;

use Hiworld\CTWing\Core\AepSdkCore;

class DeviceEvent
{
    protected $appKey;
    protected $appSecret;
    protected $masterKey;

    public function __construct($appKey = null, $appSecret = null, $masterKey = null)
    {
        // 从配置文件中获取默认值，若未传入参数则使用配置值
        $this->appKey = $appKey ?? config('ctwing.app_key');
        $this->appSecret = $appSecret ?? config('ctwing.app_secret');
        $this->masterKey = $masterKey ?? config('ctwing.master_key');
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  {
    // "productId":"10000088",//必填
    // "deviceId":"10000088001",//必填
    // "eventType":1,//非必填
    // "startTime":"1538981624878",//必填
    // "endTime":"1539575396505",//必填
    // "pageSize":10,//必填
    // "pageTimestamp":"1539575396505"//非必填
    // }
    // //eventType:（int）LWM2M,MQTT,TUP协议可选填: 1：信息 2：警告 3：故障 T-link协议可选填: 1：告警事件(普通级) 2：告警事件(重大级) 3：告警事件(严重级)；匹配所有事件类型可不填写该参数。

    public function QueryDeviceEventList($body)
    {
        $path="/aep_device_event/device/events";
        $headers = ["MasterKey" => $this->masterKey];

        $param = null;
        if (is_array($body)) {
            $body = json_encode($body);
        }
        $version ="20210327064751";

        return AepSdkCore::sendSDkRequest($path, $headers, $param, $body, $version, $this->appKey, $this->appSecret, "POST");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  {
    // "productId":"10000088",//必填
    // "deviceId":"10000088001",//必填
    // "eventType":1,//非必填
    // "startTime":"1538981624878",//必填
    // "endTime":"1539575396505"//必填
    // }
    // //eventType:（int）LWM2M,MQTT,TUP协议可选填: 1：信息 2：警告 3：故障 T-link协议可选填: 1：告警事件(普通级) 2：告警事件(重大级) 3：告警事件(严重级)；匹配所有事件类型可不填写该参数。
    // //startTime与endTime之间最大的时间差为31天
    public function QueryDeviceEventTotal($appKey, $appSecret, $MasterKey, $body)
    {
        $path="/aep_device_event/device/events/total";
        $headers = ["MasterKey" => $this->masterKey];

        $param=null;
        $version ="20210327064755";

        return AepSdkCore::sendSDkRequest($path, $headers, $param, $body, $version, $this->appKey, $this->appSecret, "POST");
    }
}
