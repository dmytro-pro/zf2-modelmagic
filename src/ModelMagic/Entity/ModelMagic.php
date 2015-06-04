<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 03.06.15
 * Time: 12:16
 */

namespace ModelMagic\Entity;

use Traversable;

class ModelMagic implements
    \ArrayAccess,
    \Countable,
    \IteratorAggregate,
    \Serializable,
    ModelMagicInterface,
    JsonSerializableInterface
{
    /**
     * Table name for this entity. Must be overridden, if EntityRepository for this entity will be used.
     */
    const TABLE_NAME = null;

    /**
     * Primary column
     */
    const PRIMARY_COLUMN = 'id';

    /** @var array */
    protected $fields = array();

    /**
     * @param array $data
     * @return $this
     */
    public function fromArray(array $data)
    {
        foreach ($data as $key => $val) {
            $this->set($key, $val);
        }
        return $this;
    }

    /**
     * @param $json
     * @return $this
     */
    public function fromJSON($json)
    {
        $decoded = json_decode($json, true);
        return $this->fromArray($decoded);
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array();
        foreach ($this->fields as $field => $val) {
            $data[$field] = $this->get($field);
        }
        return $data;
    }

    /**
     * Alias for ZF2 ArraySerializable interface[1].
     *
     * @param $data
     * @return ModelMagic
     */
    public function exchangeArray($data)
    {
        return $this->fromArray($data);
    }

    /**
     * Alias for ZF2 ArraySerializable interface[2].
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->toArray();
    }

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
    public function get($key)
    {
        return isset($this->$key) ? $this->fields[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->fields[$key] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (method_exists($this, 'get' . $name)) {
            return $this->{'get' . $name}();
        } else {
            return $this->get($name);
        }
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        if (method_exists($this, 'set' . $name)) {
            $this->{'set' . $name}($value);
        } else {
            $this->set($name, $value);
        }
    }

    /**
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->fields[$key]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->fields[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->fields[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->fields[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->fields);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->fields);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->fields = unserialize($serialized);
    }

    public function __debuginfo()
    {
        return array($this->fields);
    }
}
