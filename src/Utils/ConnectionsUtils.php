<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Utils;

use Igor360\UniswapV2Connector\Connections\ConfigCredentials;
use Igor360\UniswapV2Connector\Interfaces\ConnectionInterface;

/**
 * Utils functions for Connections namespace
 */
trait ConnectionsUtils
{

    /**
     * @param ?ConnectionInterface $credentials
     * @return void
     */
    protected function handleEmptyConnection(?ConnectionInterface &$credentials): void
    {
        if (is_null($credentials)) {
            $credentials = new ConfigCredentials();
        }
    }

}
