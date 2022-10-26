<?php

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Igor360\NftEthPhpConnector\Models\Contract as ContractModel;
use Igor360\NftEthPhpConnector\Contracts\Contract;
use Igor360\NftEthPhpConnector\Services\EthereumService;

class ContractResource extends Resource
{

    public function __construct(?string $contractAddress, Contract $contract)
    {
        $this->service = $contract;
        $this->setAddressOrHash($contractAddress);
    }

    public function load(): self
    {
        return $this;
    }

    public function model(): string
    {
        return ContractModel::class;
    }

    public function validateAddressOrHash(): void
    {
        if (!preg_match(EthereumService::ETHEREUM_ADDRESS, $this->getAddressOrHash())) {
            throw new InvalidAddressException();
        }
    }
}