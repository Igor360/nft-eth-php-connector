<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Utils;

use Igor360\NftEthPhpConnector\Exceptions\InvalidMethodCallException;

/**
 * @trait GettersToFields
 * @description Convert class fields to class getters
 */
trait GettersToFields
{
    /**
     * @param string $name
     * @return void
     * @throws InvalidMethodCallException
     */
    public function __get(string $name)
    {
        $functionName = "get" . ucfirst($name);
        try {
            if (method_exists($this, $functionName)) {
                return $this->$functionName();
            }
            return $this->$name;
        } catch (\Exception $exception) {
            throw new InvalidMethodCallException();
        }
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }
}
