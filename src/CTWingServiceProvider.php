<?php

namespace Hiworld\CTWing;

use Hiworld\CTWing\Aep\DeviceCommandCancel;
use Hiworld\CTWing\Aep\DeviceManagement;
use Hiworld\CTWing\Aep\DeviceModel;
use Hiworld\CTWing\Aep\DeviceStatus;
use Hiworld\CTWing\Aep\ProductManagement;
use Illuminate\Support\ServiceProvider;
use Hiworld\CTWing\Aep\CommandModbus;
use Hiworld\CTWing\Aep\DeviceCommand;

class CTWingServiceProvider extends ServiceProvider
{
    public function register()
    {
        // 合并用户的自定义配置和 package 默认配置
        $this->mergeConfigFrom(__DIR__.'/../config/ctwing.php', 'ctwing');

        // 注册 CommandModbus 服务
        $this->app->singleton('commandModbus', function () {
            return new CommandModbus();
        });

        // 注册 DeviceCommand 服务
        $this->app->singleton('deviceCommand', function () {
            return new DeviceCommand();
        });

        // 注册 DeviceCommandCancel 服务
        $this->app->singleton('deviceCommandCancel', function () {
            return new DeviceCommandCancel();
        });

        // 注册 DeviceEvent 服务
        $this->app->singleton('deviceEvent', function () {
            return new DeviceEvent();
        });

        // 注册 DeviceManagement 服务
        $this->app->singleton('deviceManagement', function () {
            return new DeviceManagement();
        });

        // 注册 DeviceModel 服务
        $this->app->singleton('deviceModel', function () {
            return new DeviceModel();
        });

        // 注册 DeviceStatus 服务
        $this->app->singleton('deviceStatus', function () {
            return new DeviceStatus();
        });

        // 注册 ProductManagement 服务
        $this->app->singleton('productManagement', function () {
            return new ProductManagement();
        });
    }

    public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__.'/../config/ctwing.php' => config_path('ctwing.php'),
        ], 'config');
    }
}
