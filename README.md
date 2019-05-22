# PHP RPC CLIENT

## 简介

PHP RPC 客户端,使用JSON对载荷进行编码和解码


## 使用方法

```php

use Tourscool\RpcClient\Client;

$client = new Client([
    'endpoints'=>[
        'tcp://192.168.1.251:20182' ,
        'tcp://192.168.1.252:2010'
        ]
    ]);

try {
    $result = $client->service('Greeting')->sayHello('Vincent');
    print_r($result);
}catch(RpcException $e){
    echo 'RPC fail';
}

```