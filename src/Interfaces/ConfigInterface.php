<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Interfaces;

interface ConfigInterface
{
    public const DATE_FORMAT = "d-m-Y H:i:s";

    public const DATE_ZONE = "Europe/Helsinki";

    public const BASE_KEY = "tokens-eth-php-connector";

    public static function get(string $key, $default = null);

    public static function toArray(): array;

}
