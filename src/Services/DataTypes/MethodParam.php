<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services\DataTypes;

/**
 * @source https://github.com/furqansiddiqui/ethereum-php
 */
class MethodParam
{
    /** @var string */
    public string $name;
    /** @var string */
    public string $type;
    /** @var bool|null */
    public ?bool $indexed = null;
}
