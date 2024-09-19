<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class DeviceCommandCancel
{
    protected $masterKey;

    public function __construct($masterKey = null)
    {
        // 从配置文件中获取默认值，若未传入参数则使用配置值
        $this->masterKey = $masterKey ?? config('ctwing.master_key');
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CancelAllCommand($body)
    {
        $path="/aep_device_command_cancel/cancelAllCommand";
        $headers = ["MasterKey" => $this->masterKey];

        $param=null;
        $version ="20230419143717";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "PUT");
    }
}
