# Twinkle\DI

实现PSR-11容器接口  

[![Build Status](https://travis-ci.org/actors315/DI.svg?branch=master)](https://travis-ci.org/actors315/DI)  

## 安装  

```
composer require twinkle/di --prefer-dist --optimize-autoloader
```

## 示例

Container  

```
$definitions = [
    'hello' => [
        'class' => \HelloWorld::class,
    ]
];
$container = new \Twinkle\DI\Container($definitions);

// 是否注入容器
isset($container['hello'])

// 获取实例
$instance = $container['hello'];
$instance->someMethod();

// 释放
unset($container['hello']);

```

ServiceLocator  

```
namespace App\Services;
class HelloService
{

    public function sayHello()
    {
        return 'hello';
    }
}

namespace App\Controllers;
use App\Services\HelloService;
use Twinkle\DI\ServiceLocatorTrait;

/**
 * Class HelloController
 * @package App\Controllers
 * @property HelloService $helloService
 */
class HelloController
{

    use ServiceLocatorTrait;

    public static function supportAutoNamespaces()
    {
        return [
            'App\\Services',
            'Twinkle\\Services'
        ];
    }

    public function indexAction()
    {
        echo $this->helloService->sayHello();
    }

}

```