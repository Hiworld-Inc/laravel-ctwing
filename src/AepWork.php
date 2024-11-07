<?php

namespace Hiworld\CTWing;

use Illuminate\Support\Facades\Facade;

class AepWork extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'AepWork';
    }

    public static function DeviceManagement()
    {
        return app('deviceManagement');
    }

    public static function DeviceCommand()
    {
        return app('deviceCommand');
    }

    public static function CommandModbus()
    {
        return app('commandModbus');
    }

    public static function DeviceEvent()
    {
        return app('deviceEvent');
    }

    public static function ProductManagement()
    {
        return app('productManagement');
    }
}
