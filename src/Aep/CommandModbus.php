<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class CommandModbus
{
    /**
     * Query the list of commands for a Modbus device.
     *
     * @param string $productId Product ID
     * @param string $deviceId Device ID
     * @param string $status Status of the command (optional)
     * @param string $startTime Start time for the query (optional)
     * @param string $endTime End time for the query (optional)
     * @param string $pageNow Current page number (optional)
     * @param string $pageSize Number of items per page (optional)
     * @return array|string|bool Response from the API
     */
    public function queryCommandList($productId, $deviceId, $status = "", $startTime = "", $endTime = "", $pageNow = "", $pageSize = "")
    {
        $path = "/aep_command_modbus/modbus/commands";

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

        return AepSdkService::sendSdkRequest($path, $param, null, $version, "GET");
    }

    /**
     * Query a specific command for a Modbus device.
     *
     * @param string $productId Product ID
     * @param string $deviceId Device ID
     * @param string $commandId Command ID
     * @return array|string|bool Response from the API
     */
    public function queryCommand($productId, $deviceId, $commandId)
    {
        $path = "/aep_command_modbus/modbus/command";

        $param = [
            "productId" => $productId,
            "deviceId" => $deviceId,
            "commandId" => $commandId
        ];

        $version = "20200904172207";

        return AepSdkService::sendSdkRequest($path, $param, null, $version, "GET");
    }

    /**
     * Cancel a command for a Modbus device.
     *
     * @param string $body JSON body containing the command details
     * @return array|string|bool Response from the API
     */
    public function cancelCommand($body)
    {
        $path = "/aep_command_modbus/modbus/cancelCommand";

        $version = "20200404012453";

        return AepSdkService::sendSdkRequest($path, null, $body, $version, "PUT");
    }

    /**
     * Create a new command for a Modbus device.
     *
     * @param string $body JSON body containing the command details
     * @return array|string|bool Response from the API
     */
    public function createCommand($body)
    {
        $path = "/aep_command_modbus/modbus/command";

        $version = "20200404012449";

        return AepSdkService::sendSdkRequest($path, null, $body, $version, "POST");
    }
}
