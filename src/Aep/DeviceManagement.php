<?php
namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class DeviceManagement
{
    protected $masterKey;

    public function __construct($masterKey = null)
    {
        // 从配置文件中获取默认值，若未传入参数则使用配置值
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

        // 实例化 AepSdkService
        $aepSdkService = new AepSdkService();

        // 传递 'GET' 作为 HTTP 方法
        return $aepSdkService->sendSdkRequest($path, $headers, $param, null, $version, 'GET');
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

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
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

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "DELETE");
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

        return  AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "PUT");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CreateDevice($body)
    {
        $path="/aep_device_management/device";
        $headers = ["MasterKey" => $this->masterKey];

        $param = null;

        $version ="20181031202117";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version,  "POST");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function BindDevice($body)
    {
        $path="/aep_device_management/bindDevice";
        $headers = ["MasterKey" => $this->masterKey];
        $param=null;
        $version ="20191024140057";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function UnbindDevice($body)
    {
        $path="/aep_device_management/unbindDevice";
        $headers = ["MasterKey" => $this->masterKey];

        $param=null;
        $version ="20191024140103";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数imei: 类型String, 参数不可以为空
    //  描述:
    public function QueryProductInfoByImei($imei)
    {
        $path="/aep_device_management/device/getProductInfoFormApiByImei";
        $headers=null;
        $param=array();
        $param["imei"]=$imei;

        $version ="20191213161859";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function ListDeviceInfo($body)
    {
        $path="/aep_device_management/listByDeviceIds";
        $headers = ["MasterKey" => $this->masterKey];

        $param=null;
        $version ="20210828062945";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function DeleteDeviceByPost($body)
    {
        $path="/aep_device_management/deleteDevice";
        $headers = ["MasterKey" => $this->masterKey];

        $param=null;
        $version ="20211009132842";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function ListDeviceActiveStatus($body)
    {
        $path="/aep_device_management/listActiveStatus";
        $headers = ["MasterKey" => $this->masterKey];
        $param=null;
        $version ="20211010063104";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function BatchCreateDevice($body)
    {
        $path="/aep_device_management/batchDevice";
        $headers = ["MasterKey" => $this->masterKey];
        $param=null;
        $version ="20230330043852";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }
}
