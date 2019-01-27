<?php
/**
 * Created by PhpStorm.
 * User: xiehuanjin
 * Date: 2019/1/12
 * Time: 14:26
 */

error_reporting(E_ALL);
$autoLoader = __DIR__ . '/../vendor/autoload.php';
if (! file_exists($autoLoader)) {
    echo "Composer autoloader not found: $autoLoader" . PHP_EOL;
    echo "Please issue 'composer install' and try again." . PHP_EOL;
    exit(1);
}
require $autoLoader;
