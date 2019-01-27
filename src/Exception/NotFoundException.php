<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2019/1/20
 * Time: 18:19
 */

namespace Twinkle\DI\Exception;


use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{

}