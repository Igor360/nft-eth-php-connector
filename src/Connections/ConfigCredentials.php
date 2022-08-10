<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Connections;

use Igor360\NftEthPhpConnector\Configs\ConfigFacade as Config;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;

class ConfigCredentials implements ConnectionInterface
{
    public function host(): string
    {
        return Config::get("uniswap-v2-connector.eth.host");
    }

    public function port(): ?int
    {
        return Config::get("uniswap-v2-connector.eth.port");
    }

    /**
     * Set ssl connection
     * @return bool
     */
    public function ssl(): bool
    {
        return (bool)Config::get('uniswap-v2-connector.eth.ssl');
    }
}
