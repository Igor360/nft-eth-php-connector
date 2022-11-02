<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Interfaces;

/**
 * @class ConnectionInterface
 * @description Declare connection credentials
 */
interface ConnectionInterface
{
    public function host(): string;

    public function port(): ?int;

    public function ssl(): bool;
}
