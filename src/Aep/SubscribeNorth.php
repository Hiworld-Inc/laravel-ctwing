<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class SubscribeNorth
{
    protected $masterKey;

    public function __construct($masterKey = null)
    {
        // 从配置文件中获取默认值，若未传入参数则使用配置值
        $this->masterKey = $masterKey ?? config('ctwing.master_key');
    }
    //参数subId: 类型long, 参数不可以为空
    //  描述:订阅记录id
    //参数productId: 类型long, 参数不可以为空
    //  描述:产品id，分组级为-1
    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:产品MasterKey
    public function GetSubscription($subId, $productId)
    {
        $path="/aep_subscribe_north/subscription";
        $headers = ['MasterKey' => $this->masterKey];

        $param = [
            'subId' => $subId,
            'productId' => $productId,
        ];

        $version ="20220624171733";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
    }

    //参数productId: 类型long, 参数不可以为空
    //  描述:产品ID
    //参数pageNow: 类型long, 参数不可以为空
    //  描述:当前页
    //参数pageSize: 类型long, 参数不可以为空
    //  描述:每页条数
    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数subType: 类型long, 参数可以为空
    //  描述:订阅类型
    //参数searchValue: 类型String, 参数可以为空
    //  描述:检索deviceId,模糊匹配
    //参数deviceGroupId: 类型String, 参数可以为空
    //  描述:
    public function GetSubscriptionsList($productId, $pageNow, $pageSize,  $subType = "", $searchValue = "", $deviceGroupId = "")
    {
        $path="/aep_subscribe_north/subscribes";
        $headers= ['MasterKey' => $this->masterKey];

        $param = [
            'productId' => $productId,
            'pageNow' => $pageNow,
            'pageSize' => $pageSize,
            'subType' => $subType,
            'searchValue' => $searchValue,
            'deviceGroupId' => $deviceGroupId,
        ];

        $version ="20220624163719";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "GET");
    }

    //参数subId: 类型String, 参数不可以为空
    //  描述:订阅记录id
    //参数deviceId: 类型String, 参数可以为空
    //  描述:设备id
    //参数productId: 类型long, 参数不可以为空
    //  描述:产品id
    //参数subLevel: 类型long, 参数不可以为空
    //  描述:订阅级别
    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:产品MasterKey
    public  function DeleteSubscription($subId, $productId, $subLevel, $MasterKey, $deviceId = "")
    {
        $path="/aep_subscribe_north/subscription";
        $headers= ['MasterKey' => $this->masterKey];

        $param = [
            'subId' => $subId,
            'deviceId' => $deviceId,
            'productId' => $productId,
            'subLevel' => $subLevel,
        ];

        $version ="20181031202023";

        return AepSdkService::sendSdkRequest($path, $headers, $param, null, $version, "DELETE");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CreateSubscription($body)
    {
        $path="/aep_subscribe_north/subscription";
        $headers= ['MasterKey' => $this->masterKey];

        $param=null;
        $version ="20181031202018";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }

    //参数MasterKey: 类型String, 参数可以为空
    //  描述:
    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CreateDestHttpUrl($body)
    {
        $path="/aep_subscribe_north/createUrl";
        $headers= ['MasterKey' => $this->masterKey];

        $param=null;
        $version ="20231109105327";

        return AepSdkService::sendSdkRequest($path, $headers, $param, $body, $version, "POST");
    }
}
