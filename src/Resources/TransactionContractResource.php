<?php

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Contracts\Contract;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;

class TransactionContractResource extends TransactionResource
{
    public function __construct(?string $hash, Contract $contractService, ConnectionInterface $credentials)
    {
        parent::__construct($hash, $credentials);
        $this->service = $contractService;
        $this->setAddressOrHash($hash);
    }
}