<?php

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Contracts\ContractsFactory;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;

class WETHResource extends ERC20Resource
{
    public function __construct(string $contractAddress, ConnectionInterface $credentials)
    {
        parent::__construct($contractAddress, $credentials);
        $this->service = ContractsFactory::make('weth', $credentials);
    }
}