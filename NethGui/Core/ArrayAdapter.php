<?php

class NethGui_Core_ArrayAdapter implements NethGui_Core_AdapterInterface, ArrayAccess, IteratorAggregate, Countable
{

    /**
     *
     * @var string
     */
    private $separator;
    private $modified;
    /**
     *
     * @var ArrayObject
     */
    private $data;
    /**
     *
     * @var NethGui_Core_SerializerInterface
     */
    private $serializer;

    public function __construct($separator, NethGui_Core_SerializerInterface $serializer)
    {
        $this->separator = $separator;
        $this->serializer = $serializer;
    }

    public function get()
    {
        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }

        if (is_null($this->data)) {
            return NULL;
        }

        return $this;
    }

    public function set($value)
    {
        if ( ! is_array($value) && ! is_null($value)) {
            throw new NethGui_Exception_Adapter('Invalid data type. Expected `array` or `NULL`, was ' . gettype($value));
        }

        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }

        if (is_null($value) && is_null($this->data))
        {
            $this->modified = FALSE;
            return;
        }

        if (is_null($value) && !is_null($this->data))
        {
            $this->modified = TRUE;
            $this->data = NULL;
            return;
        }

        if (is_null($this->data)) {
            $this->data = new ArrayObject($value);
            $this->modified = TRUE;
        } elseif ($this->data->getArrayCopy() !== $value) {
            $this->data->exchangeArray($value);
            $this->modified = TRUE;
        }

    }

    public function delete()
    {
        $this->set(NULL);
    }

    public function isModified()
    {
        return $this->modified === TRUE;
    }

    public function save()
    {
        if ( ! $this->isModified()) {
            return;
        }

        if (is_object($this->data)) {
            $value = implode($this->separator, $this->data->getArrayCopy());
        } else {
            $value = NULL;
        }

        $this->serializer->write($value);

        $this->modified = FALSE;
    }

    public function count()
    {
        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }

        if (is_null($this->data)) {
            return 0;
        }

        return count($this->data);
    }

    public function getIterator()
    {
        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }

        if (is_null($this->data)) {
            return new ArrayIterator(array());
        }

        return $this->data->getIterator();
    }

    public function offsetExists($offset)
    {
        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }
        return is_object($this->data) && $this->data->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }

        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }

        return NULL;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }

        if (is_null($this->data)) {
            $this->data = new ArrayObject();
        }

        $this->data[$offset] = $value;
        $this->modified = TRUE;
    }

    public function offsetUnset($offset)
    {
        if (is_null($this->modified)) {
            $this->lazyInitialization();
        }

        if (isset($this->data[$offset])) {
            unset($this->data[$offset]);
            $this->modified = TRUE;
        }
    }

    private function lazyInitialization()
    {
        $value = $this->serializer->read();

        if (is_null($value)) {
            $this->data = NULL;
        } elseif ($value === '') {
            $this->data = new ArrayObject();
        } else
        {
            $this->data = new ArrayObject(explode($this->separator, $value));
        }

        $this->modified = FALSE;
    }

}