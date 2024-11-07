<?php
namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class ProductManagement
{
    //参数productId: 类型long, 参数不可以为空
    //  描述:
    public function QueryProduct($productId)
    {
        $path="/aep_product_management/product";
        $param = ["productId" => $productId];

        $version ="20181031202055";

        return AepSdkService::sendSdkRequest($path, $param, null, $version, "GET");
    }

    //参数searchValue: 类型String, 参数可以为空
    //  描述:产品id或者产品名称
    //参数pageNow: 类型long, 参数可以为空
    //  描述:当前页数
    //参数pageSize: 类型long, 参数可以为空
    //  描述:每页记录数
    public function QueryProductList($searchValue = "", $pageNow = "", $pageSize = "")
    {
        $path="/aep_product_management/products";
        $param = [
            'searchValue' => $searchValue,
            'pageNow' => $pageNow,
            'pageSize' => $pageSize,
        ];

        $version ="20190507004824";

        return AepSdkService::sendSdkRequest($path, $param, null, $version, "GET");
    }

    //参数MasterKey: 类型String, 参数不可以为空
    //  描述:MasterKey在该设备所属产品的概况中可以查看
    //参数productId: 类型long, 参数不可以为空
    //  描述:
    public function DeleteProduct($productId)
    {
        $path="/aep_product_management/product";
        $param = ["productId" => $productId];

        $version ="20181031202029";

        return AepSdkService::sendSdkRequest($path, $param, null, $version, "DELETE");
    }

    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function CreateProduct($body)
    {
        $path="/aep_product_management/product";
        $param=null;

        $version ="20220924042921";

        return AepSdkService::sendSdkRequest($path, $param, $body, $version, "POST");
    }

    //参数body: 类型json, 参数不可以为空
    //  描述:body,具体参考平台api说明
    public function UpdateProduct($body)
    {
        $path="/aep_product_management/product";
        $param=null;

        $version ="20220924043504";

        return AepSdkService::sendSdkRequest($path, $param, $body, $version, "PUT");
    }
}
