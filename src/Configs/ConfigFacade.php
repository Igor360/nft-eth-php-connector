<?php

namespace Igor360\NftEthPhpConnector\Configs;

use Igor360\UniswapV2Connector\Exceptions\InvalidConstantException;
use Igor360\UniswapV2Connector\Exceptions\InvalidImplementationClassException;
use Igor360\UniswapV2Connector\Exceptions\InvalidMethodCallException;
use Igor360\UniswapV2Connector\Interfaces\ConfigInterface;
use ReflectionClass;

abstract class ConfigFacade
{
    private static string $configSource = Config::class;

    public static function changeConfigSource(string $newSource): void
    {
        if (is_subclass_of($newSource, ConfigInterface::class)) {
            self::$configSource = $newSource;
        }
        throw new InvalidImplementationClassException("New source config is not realize ConfigInterface");
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(self::$configSource, $name)) {
            $class = self::$configSource;
            return $class::$name(...$arguments);
        }
        throw new InvalidMethodCallException();
    }

    public static function getConstant($name)
    {
        $reflector = new ReflectionClass(self::$configSource);
        $constants = $reflector->getConstants();
        if (array_key_exists($name, $constants)) {
            return $constants[$name];
        }
        throw new InvalidConstantException();
    }
}
