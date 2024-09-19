<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class CommandModbus
{
    protected $masterKey;

    public function __construct($masterKey = null)
    {
        // 从配置文件中获取默认值，若未传入参数则使用配置文件中的值
        $this->masterKey = $masterKey ?? config('ctwing.master_key');
    }

    //参数status: 类型String, 参数可以为空
    //  描述:状态可选填： 1：指令已保存 2：指令已发送 3：指令已送达 4：指令已完成 6：指令已取消 999：指令失败
    //参数startTime: 类型String, 参数可以为空
    //  描述:
    //参数endTime: 类型String, 参数可以为空
    //  描述:
    //参数pageNow: 类型String, 参数可以为空
    //  描述:
    //参数pageSize: 类型String, 参数可以为空
    //  描述:
    public function QueryCommandList($productId, $deviceId, $status = "", $startTime = "", $endTime = "", $pageNow = "", $pageSize = "")
    {
        $path = "/aep_command_modbus/modbus/commands";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "productId" => $productId,
            "deviceId" => $deviceId,
            "status" => $status,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "pageNow" => $pageNow,
            "pageSize" => $pageSize
        ];

        $version = "20200904171008";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数productId: 类型long, 参数不可以为空
    //  描述:产品ID
    //参数deviceId: 类型String, 参数不可以为空
    //  描述:设备ID
    //参数commandId: 类型String, 参数不可以为空
    //  描述:指令ID
    public function QueryCommand($productId, $deviceId, $commandId)
    {
        $path = "/aep_command_modbus/modbus/command";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "productId" => $productId,
            "deviceId" => $deviceId,
            "commandId" => $commandId
        ];

        $version = "20200904172207";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CancelCommand($body)
    {
        $path = "/aep_command_modbus/modbus/cancelCommand";
        $headers = ["MasterKey" => $this->masterKey];

        $version = "20200404012453";

        return AepSdkService::sendSdkRequest($path, $headers, null, $body, $version, "PUT");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CreateCommand($body)
    {
        $path = "/aep_command_modbus/modbus/command";
        $headers = ["MasterKey" => $this->masterKey];

        $version = "20200404012449";

        return AepSdkService::sendSdkRequest($path, $headers, null, $body, $version, "POST");
    }
}
