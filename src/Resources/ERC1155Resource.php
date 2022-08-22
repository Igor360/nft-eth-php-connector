<?php

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Contracts\ContractsFactory;
use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;
use Igor360\NftEthPhpConnector\Models\Token;
use Igor360\NftEthPhpConnector\Services\EthereumService;

class ERC1155Resource extends Resource
{
    public function __construct(?string $contractAddress, ConnectionInterface $credentials)
    {
        $this->service = ContractsFactory::make('erc1155', $credentials);
        $this->setAddressOrHash($contractAddress);
    }

    public function load(): void
    {}

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