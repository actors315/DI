<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2019/1/13
 * Time: 10:08
 */

namespace Twinkle\DI;


use Twinkle\DI\Exception\NotFoundException;


trait ServiceLocatorTrait
{

    public static function supportAutoNamespaces()
    {
        $namespace = __NAMESPACE__;
        return [$namespace];
    }

    public static function supportedClassSuffix()
    {
        return [
            'Service',
            'Model',
            'Facade'
        ];
    }

    public static function getContainer()
    {
        return Tools::getContainer();
    }

    /**
     * @param string $name 属性名称
     * @return mixed
     * @todo 父类如果有__get方法, 在判断失败时调用父类的
     */
    public function __get($name)
    {
        if ($this->isSupportedClassSuffix($name)) {
            return $this->getByCalledClass($name);
        }
    }

    protected function getByCalledClass($propertyName)
    {
        $className = ucwords($propertyName);
        foreach (static::supportAutoNamespaces() as $namespace) {
            if (class_exists("{$namespace}\\{$className}")) {
                return static::getContainer()->reflector("{$namespace}\\{$className}");
            }
        }

        throw new NotFoundException("No entry or class found for {$propertyName}");
    }


    /**
     * 是否支持以属性的方式加载
     * @param $name
     * @return bool
     */
    protected function isSupportedClassSuffix($name)
    {
        $suffixList = static::supportedClassSuffix();

        if (!in_array($name, $suffixList)) {
            foreach ($suffixList as $item) {
                if ($item == substr($name, -strlen($item))) {
                    return true;
                }
            }
        }

        return false;
    }

}