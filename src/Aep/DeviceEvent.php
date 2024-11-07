<?php
namespace Hiworld\CTWing;

use Hiworld\CTWing\Services\AepSdkService;

class DeviceEvent
{
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

        $param = null;
        $version ="20210327064751";

        return AepSdkService::sendSdkRequest($path, $param, $body, $version, "POST");
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
    public function QueryDeviceEventTotal($body)
    {
        $path="/aep_device_event/device/events/total";
        $headers = ["MasterKey" => $this->masterKey];

        $param=null;
        $version ="20210327064755";

        return AepSdkService::sendSdkRequest($path, $param, $body, $version, "POST");
    }
}
