<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Core\AepSdkCore;
class DeviceCommand
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
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CreateCommand($body)
    {
        $path = "/aep_device_command/command";
        $headers = ["MasterKey" => $this->masterKey];

        $version = "20190712225145";

        if (is_array($body)) {
            $body = json_encode($body);
        }

        return AepSdkCore::sendSDkRequest(
            $path,
            $headers,
            null,
            $body,
            $version,
            $this->appKey,
            $this->appSecret,
            "POST"
        );
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数commandId: 类型String, 参数不可以为空
    //  描述:创建指令成功响应中返回的id，
    //参数productId: 类型long, 参数不可以为空
    //  描述:
    //参数deviceId: 类型String, 参数不可以为空
    //  描述:设备ID
    public function QueryCommand($commandId, $productId, $deviceId)
    {
        $path = "/aep_device_command/command";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "commandId" => $commandId,
            "productId" => $productId,
            "deviceId" => $deviceId
        ];

        $version = "20190712225241";

        return AepSdkCore::sendSDkRequest(
            $path,
            $headers,
            $param,
            null,
            $version,
            $this->appKey,
            $this->appSecret,
            "GET"
        );
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CancelCommand($body)
    {
        $path = "/aep_device_command/cancelCommand";
        $headers = ["MasterKey" => $this->masterKey];

        $version = "20190615023142";

        return AepSdkCore::sendSDkRequest(
            $path,
            $headers,
            null,
            $body,
            $version,
            $this->appKey,
            $this->appSecret,
            "PUT"
        );
    }
}
