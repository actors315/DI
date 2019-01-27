<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2019/1/27
 * Time: 11:46
 */

namespace Twinkle\DI;


use PHPUnit\Framework\TestCase;
use Twinkle\DI\Exception\NotFoundException;

/**
 * Class ContainerTest
 * @package Twinkle\DI
 * @property HelloService $helloService
 * @property NotFoundException $notFoundService
 */
class ServiceLocatorTest extends TestCase
{
    use ServiceLocatorTrait;

    public function testLocator()
    {
        $hello = $this->helloService;
        $this->assertInstanceOf(HelloService::class,$hello);
        $this->expectException(NotFoundException::class);
        $this->notFoundService;
    }
}

class HelloService
{

}