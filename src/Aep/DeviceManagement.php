<?php
namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Core\AepSdkCore;

class DeviceManagement
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

    // 参数productId: 类型long, 参数不可以为空
    // 描述:
    // 参数searchValue: 类型String, 参数可以为空
    // 描述: 可选设备标识符
    // 参数pageNow: 类型long, 参数可以为空
    // 描述: 当前页数
    // 参数pageSize: 类型long, 参数可以为空
    // 描述: 每页记录数, 最大100
    public function QueryDeviceList($productId, $searchValue = "", $pageNow = "", $pageSize = "")
    {
        $path = "/aep_device_management/devices";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "productId" => $productId,
            "searchValue" => $searchValue,
            "pageNow" => $pageNow,
            "pageSize" => $pageSize
        ];

        $version = "20190507012134";

        return AepSdkCore::sendSDkRequest($path, $headers, $param, null, $version, $this->appKey, $this->appSecret, "GET");
    }

    // 参数deviceId: 类型String, 参数不可以为空
    // 描述: 设备ID
    // 参数productId: 类型long, 参数不可以为空
    // 描述: 产品ID
    public function QueryDevice($deviceId, $productId)
    {
        $path = "/aep_device_management/device";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "deviceId" => $deviceId,
            "productId" => $productId
        ];

        $version = "20181031202139";

        return AepSdkCore::sendSDkRequest($path, $headers, $param, null, $version, $this->appKey, $this->appSecret, "GET");
    }

    // 参数productId: 类型long, 参数不可以为空
    // 描述: 产品ID
    // 参数deviceIds: 类型String, 参数不可以为空
    // 描述: 设备ID列表，以逗号隔开
    public function DeleteDevice($productId, $deviceIds)
    {
        $path = "/aep_device_management/device";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "productId" => $productId,
            "deviceIds" => $deviceIds
        ];

        $version = "20181031202131";

        return AepSdkCore::sendSDkRequest($path, $headers, $param, null, $version, $this->appKey, $this->appSecret, "DELETE");
    }

    public function UpdateDevice($deviceId, $body)
    {
        $path="/aep_device_management/device";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "deviceId" => $deviceId,
            "body" => $body
        ];

        $version ="20181031202122";

        return  AepSdkCore::sendSDkRequest($path, $headers, $param, $body, $version, $this->appKey, $this->appSecret, "PUT");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CreateDevice($body)
    {
        $path="/aep_device_management/device";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "body" => $body
        ];
        $version ="20181031202117";

        return AepSdkCore::sendSDkRequest($path, $headers, $param, $body, $version, $this->appKey, $this->appSecret, "POST");
    }
}
