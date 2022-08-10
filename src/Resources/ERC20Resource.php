<?php

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Contracts\ContractsFactory;
use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;
use Igor360\NftEthPhpConnector\Models\Token;
use Igor360\NftEthPhpConnector\Services\EthereumService;

class ERC20Resource extends Resource
{

    public function __construct(?string $contractAddress, ConnectionInterface $credentials)
    {
        $this->service = ContractsFactory::make('erc20', $credentials);
        $this->setAddressOrHash($contractAddress);
    }

    public function load(): void
    {
        $this->makeMap();
    }

    public function model(): string
    {
        return Token::class;
    }

    public function validateAddressOrHash(): void
    {
        if (!preg_match(EthereumService::ETHEREUM_ADDRESS, $this->getAddressOrHash())) {
            throw new InvalidAddressException();
        }
    }
}