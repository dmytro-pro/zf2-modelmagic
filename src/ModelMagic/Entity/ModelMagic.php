<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 03.06.15
 * Time: 12:16
 */

namespace ModelMagic\Entity;


class ModelMagic
{
    /** @var array */
    protected $fields = array();

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->fields[$key]);
    }

    /**
     * @param string $key
     *
     * @return null
     */
    public function __get($key)
    {

        return isset($this->$key) ? $this->fields[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->fields[$key] = $value;
    }
}