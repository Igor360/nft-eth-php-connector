<?php

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Contracts\ERC20Contract;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;
use Igor360\NftEthPhpConnector\Services\EthereumService;

class TransactionTokenResource extends TransactionResource
{
    public function __construct(?string $hash, ConnectionInterface $credentials)
    {
        parent::__construct($hash, $credentials);
        $this->service = new ERC20Contract($credentials);
        $this->setAddressOrHash($hash);
    }

}