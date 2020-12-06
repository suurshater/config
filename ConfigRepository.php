<?php

/**
 *
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Suurshater\Config;

/**
 * Description of ConfigRepository
 *
 * @author Avicii
 */
use Countable;
use ArrayAccess;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Config\Repository;

class ConfigRepository implements Countable, ArrayAccess, Repository
{

    /**
     *
     * @var array
     */
    protected $items = [];

    /**
     *
     * @param type $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     *
     * @return type
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ( is_array($key) ) {
            return $this->getMany($key);
        }

        return Arr::get($this->items, $key, $default);
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return Arr::has($this->items, $key);
    }

    /**
     *
     * @param type $key
     * @param type $value
     */
    public function set($key, $value = null)
    {
        $array = !is_null($value) ? [$key => $value] : $value;

        foreach ( $array as $key => $value ) {
            Arr::set($this->items, $key, $value);
        }
    }

    /**
     * Prepend an item onto a value
     *
     * @param string $key
     * @param mixed  $value
     */
    public function prepend($key, $value)
    {
        $array = (array) $this->get($key);

        array_unshift($array, $value);

        $this->set($key, $array);
    }

    /**
     * Alias of push method
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function append($key, $value)
    {
        $this->push($key, $value);
    }

    /**
     * Push an item onto a value
     *
     * @param type $key
     * @param type $value
     */
    public function push($key, $value)
    {
        $array = (array) $this->get($key);

        $array[] = $value;

        $this->set($key, $array);
    }

    /**
     *
     * @param array $keys
     * @return array
     */
    public function getMany($keys)
    {
        $configs = [];

        foreach ( $keys as $key => $default ) {
            if ( is_numeric($key) ) {
                list($key, $default) = [$default, null];
            }

            $configs[$key] = Arr::get($this->items, $key, $default);
        }

        return $configs;
    }

    /**
     * Alias of has method
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Alias of get method
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Alias of set method
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        if ( $this->offsetExists($offset) ) {
            Arr::forget($this->items, $offset);
        }
    }

}
