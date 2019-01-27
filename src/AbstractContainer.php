<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2019/1/20
 * Time: 18:16
 */

namespace Twinkle\DI;

use Psr\Container\ContainerInterface;
use Twinkle\DI\Exception\ContainerException;
use Twinkle\DI\Exception\NotFoundException;

abstract class AbstractContainer implements ContainerInterface
{

    protected $resolvedEntries = [];

    /**
     * @var array
     */
    protected $definitions = [];

    public function __construct($definitions = [])
    {
        foreach ($definitions as $id => $definition) {
            $this->injection($id, $definition);
        }
    }

    public function has($id)
    {
        return isset($this->definitions[$id]);
    }

    /**
     * @param string $id
     * @param string | array | callable $concrete
     * @throws ContainerException
     */
    public function injection($id, $concrete)
    {
        if (!is_string($id)) {
            throw new \InvalidArgumentException(sprintf(
                'The id parameter must be of type string, %s given',
                is_object($id) ? get_class($id) : gettype($id)
            ));
        }

        if (is_array($concrete) && !isset($concrete['class'])) {
            throw new ContainerException('数组必须包含类定义');
        }

        $this->definitions[$id] = $concrete;
    }

    protected function make($name)
    {

        if (isset($this->resolvedEntries[$name])) {
            return $this->resolvedEntries[$name];
        }

        $definition = $this->definitions[$name];
        $params = [];
        if (is_array($definition) && isset($definition['class'])) {
            $params = $definition;
            $definition = $definition['class'];
            unset($params['class']);
        }

        $object = $this->reflector($definition, $params);

        return $this->resolvedEntries[$name] = $object;
    }

    public function reflector($concrete, array $params = [])
    {
        if ($concrete instanceof \Closure) {
            return $concrete($params);
        } elseif (is_string($concrete)) {
            $reflection = new \ReflectionClass($concrete);
            $dependencies = $this->getDependencies($reflection);
            foreach ($params as $index => $value) {
                $dependencies[$index] = $value;
            }
            return $reflection->newInstanceArgs($dependencies);
        } elseif (is_object($concrete)) {
            return $concrete;
        }

        throw new ContainerException('获取对象实例失败');
    }

    /**
     * @param \ReflectionClass $reflection
     * @return array
     */
    private function getDependencies($reflection)
    {
        $dependencies = [];
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            $parameters = $constructor->getParameters();
            $dependencies = $this->getParametersByDependencies($parameters);
        }

        return $dependencies;
    }

    /**
     *
     * 获取构造类相关参数的依赖
     * @param array $dependencies
     * @return array $parameters
     * */
    private function getParametersByDependencies(array $dependencies)
    {
        $parameters = [];
        foreach ($dependencies as $param) {
            if ($param->getClass()) {
                $paramName = $param->getClass()->name;
                $paramObject = $this->reflector($paramName);
                $parameters[] = $paramObject;
            } elseif ($param->isArray()) {
                if ($param->isDefaultValueAvailable()) {
                    $parameters[] = $param->getDefaultValue();
                } else {
                    $parameters[] = [];
                }
            } elseif ($param->isCallable()) {
                if ($param->isDefaultValueAvailable()) {
                    $parameters[] = $param->getDefaultValue();
                } else {
                    $parameters[] = function ($arg) {
                    };
                }
            } else {
                if ($param->isDefaultValueAvailable()) {
                    $parameters[] = $param->getDefaultValue();
                } else {
                    if ($param->allowsNull()) {
                        $parameters[] = null;
                    } else {
                        $parameters[] = false;
                    }
                }
            }
        }
        return $parameters;
    }
}