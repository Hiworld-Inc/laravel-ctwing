# CTwing for Laravel

> 目前只完成了设备列表和下发指令的功能

## 框架要求
- Laravel >=10.0

## 安装
```shell
composer require hiworld/ctwing
```

# 配置

```shell
php artisan vendor:publish --provider="Hiworld\\CTWing\\CTWingServiceProvider"
```

` .env ` 文件
```bash
CTWING_APP_KEY=
CTWING_APP_SECRET=
CTWING_MASTER_KEY=
```
## 使用

DeviceManagement使用：
```php
use Hiworld\CTWing\AepWork;

$productId = '';
$app = AepWork::DeviceManagement();
$app->QueryDeviceList($productId)
```

DeviceCommand使用：
```php
use Hiworld\CTWing\AepWork;

$body = [];
$app = AepWork::DeviceCommand();
$response = $app->CreateCommand($body)
```


## LICENSE
MIT
