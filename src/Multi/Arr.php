<?php namespace Multi;

class Arr implements \ArrayAccess {

    protected $_data = array();
    protected $_delimiter = '.';

    public function __construct(array $arr = null, $delimiter = null)
    {
        if ($arr !== null)
        {
            $this->setArray($arr);
        }
    }

    protected function _get($path, array $array = null)
    {
        if ($array === null)
        {
            $array = $this->_data;
        }

        if (array_key_exists($path, $array))
        {
            // No need to do extra processing
            return $array[$path];
        }

        $delimiter = $this->getDelimiter();

        // Eliminate any spaces between delimiters.
        $path = preg_replace('/\s?' . preg_quote($delimiter) . '\s?/', $delimiter, $path);

        // Split the keys by delimiter
        $keys = explode($delimiter, $path);

        do
        {
            $key = array_shift($keys);

            if (ctype_digit($key))
            {
                // Make the key an integer
                $key = (int) $key;
            }

            if (isset($array[$key]))
            {
                if ($keys)
                {
                    if (is_array($array[$key]))
                    {
                        // Dig down into the next part of the path
                        $array = $array[$key];
                    }
                    else
                    {
                        // Unable to dig deeper
                        break;
                    }
                }
                else
                {
                    // Found the path requested
                    return $array[$key];
                }
            }
            else
            {
                // Unable to dig deeper
                break;
            }
        }
        while ($keys);

        // Unable to find the value requested
        return null;
    }

    protected function _set( & $array, $path, $value)
    {
        $delimiter = $this->getDelimiter();

        // Split the keys by delimiter
        $keys = explode($delimiter, $path);

        // Set current $array to inner-most array path
        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if (ctype_digit($key))
            {
                // Make the key an integer
                $key = (int) $key;
            }

            if ( ! isset($array[$key]))
            {
                $array[$key] = array();
            }

            $array =& $array[$key];
        }

        // Set key on inner-most array
        $array[array_shift($keys)] = $value;
    }

    protected function _unset( & $array, $path)
    {
        $delimiter = $this->getDelimiter();

        // Split the keys by delimiter
        $keys = explode($delimiter, $path);

        // Set current $array to inner-most array path
        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if (ctype_digit($key))
            {
                // Make the key an integer
                $key = (int) $key;
            }

            if (isset($array[$key]))
            {
                $array =& $array[$key];
            }
        }

        // Set key on inner-most array
        unset($array[array_shift($keys)]);
    }

    public function setArray(array $arr)
    {
        $this->_data = $arr;

        return $this;
    }

    public function getArray()
    {
        return $this->_data;
    }

    public function setDelimiter(array $delimiter)
    {
        $this->_delimiter = $delimiter;

        return $this;
    }

    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    public function offsetSet($key, $value)
    {
        $this->_set($this->_data, $key, $value);
    }

    public function offsetGet($key)
    {
        return $this->_get($key);
    }

    public function offsetUnset($key)
    {
        $this->_unset($this->_data, $key);
    }

    public function offsetExists($key)
    {
        return ($this->_get($key) !== null);
    }

}