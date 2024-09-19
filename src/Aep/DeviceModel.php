<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class DeviceModel
{
    protected $masterKey;

    public function __construct($masterKey = null)
    {
        // 从配置文件中获取默认值，若未传入参数则使用配置值
        $this->masterKey = $masterKey ?? config('ctwing.master_key');
    }

    // 参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    // 参数productId: 类型long, 参数不可以为空
    //  描述:
    // 参数searchValue: 类型String, 参数可以为空
    //  描述:可填值：属性名称，属性标识符
    // 参数pageNow: 类型long, 参数可以为空
    //  描述:当前页数
    // 参数pageSize: 类型long, 参数可以为空
    //  描述:每页记录数
    public function queryPropertyList($productId, $searchValue = "", $pageNow = "", $pageSize = "")
    {
        $path = "/aep_device_model/dm/app/model/properties";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "productId" => $productId,
            "searchValue" => $searchValue,
            "pageNow" => $pageNow,
            "pageSize" => $pageSize
        ];

        $version = "20190712223330";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
    }

    // 参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    // 参数productId: 类型long, 参数不可以为空
    //  描述:
    // 参数searchValue: 类型String, 参数可以为空
    //  描述:可填： 服务Id, 服务名称,服务标识符
    // 参数serviceType: 类型long, 参数可以为空
    //  描述:服务类型
    // 参数pageNow: 类型long, 参数可以为空
    //  描述:当前页数
    // 参数pageSize: 类型long, 参数可以为空
    //  描述:每页记录数
    public function queryServiceList($productId, $searchValue = "", $serviceType = "", $pageNow = "", $pageSize = "")
    {
        $path = "/aep_device_model/dm/app/model/services";
        $headers = ["MasterKey" => $this->masterKey];

        $param = [
            "productId" => $productId,
            "searchValue" => $searchValue,
            "serviceType" => $serviceType,
            "pageNow" => $pageNow,
            "pageSize" => $pageSize
        ];

        $version = "20190712224233";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
    }
}
