<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class DeviceStatus
{
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function QueryDeviceStatus($body)
    {
        $path="/aep_device_status/deviceStatus";
        $headers=null;
        $param=null;

        $version ="20181031202028";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function QueryDeviceStatusList($body)
    {
        $path="/aep_device_status/deviceStatusList";
        $headers=null;
        $param=null;

        $version ="20181031202403";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function getDeviceStatusHisInTotal($body)
    {
        $path="/aep_device_status/api/v1/getDeviceStatusHisInTotal";
        $headers=null;
        $param=null;

        $version ="20190928013529";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function getDeviceStatusHisInPage($body)
    {
        $path="/aep_device_status/getDeviceStatusHisInPage";
        $headers=null;
        $param=null;

        $version ="20190928013337";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }
}
