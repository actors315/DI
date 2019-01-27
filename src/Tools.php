<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2019/1/27
 * Time: 12:33
 */

namespace Twinkle\DI;


class Tools
{
    protected static $container;

    public static function getContainer()
    {
        if (self::$container == null) {
            self::$container = new Container();
        }
        return self::$container;
    }

}