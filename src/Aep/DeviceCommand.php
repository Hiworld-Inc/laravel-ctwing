<?php

namespace Hiworld\CTWing\Aep;

use Hiworld\CTWing\Services\AepSdkService;

class DeviceCommand
{
    /**
     * Create a device command.
     *
     * @param array|string $body JSON body, cannot be null. Refer to platform API documentation for details.
     * @return mixed
     */
    public function CreateCommand($body)
    {
        $path = "/aep_device_command/command";
        $version = "20190712225145";

        if (empty($body)) {
            throw new \InvalidArgumentException("Body cannot be empty");
        }

        return AepSdkService::sendSdkRequest($path, null, $body, $version, 'POST');
    }

    /**
     * Query a device command.
     *
     * @param string $commandId Command ID, cannot be null.
     * @param string $productId Product ID, cannot be null.
     * @param string $deviceId Device ID, cannot be null.
     * @return mixed
     */
    public function QueryCommand($commandId, $productId, $deviceId)
    {
        $path = "/aep_device_command/command";

        if (empty($commandId) || empty($productId) || empty($deviceId)) {
            throw new \InvalidArgumentException("Command ID, Product ID, and Device ID cannot be empty");
        }

        $param = [
            "commandId" => $commandId,
            "productId" => $productId,
            "deviceId" => $deviceId
        ];

        $version = "20190712225241";

        return AepSdkService::sendSdkRequest($path, $param, null, $version, "GET");
    }

    /**
     * Cancel a device command.
     *
     * @param array|string $body JSON body, cannot be null. Refer to platform API documentation for details.
     * @return mixed
     */
    public function CancelCommand($body)
    {
        $path = "/aep_device_command/cancelCommand";
        $version = "20190615023142";

        if (empty($body)) {
            throw new \InvalidArgumentException("Body cannot be empty");
        }

        if (is_array($body)) {
            $body = json_encode($body);
        }

        return AepSdkService::sendSdkRequest($path, null, $body, $version, "PUT");
    }
}
