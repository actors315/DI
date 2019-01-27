<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2019/1/20
 * Time: 18:25
 */

namespace Twinkle\DI;


use Twinkle\DI\Exception\NotFoundException;

class Container extends AbstractContainer implements \ArrayAccess
{

    public function get($id)
    {

        if (!$this->has($id)) {
            throw new NotFoundException("No entry or class found for {$id}");
        }

        $instance = $this->make($id);

        return $instance;
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->injection($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->resolvedEntries[$offset]);
        unset($this->definitions[$offset]);
    }
}