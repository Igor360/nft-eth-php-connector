<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Contracts;

use Igor360\NftEthPhpConnector\Exceptions\InvalidContractInstanceKeyException;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;
use Igor360\NftEthPhpConnector\Services\ContractService;

abstract class ContractsFactory
{
    protected const ContractClasses = [
        'erc20' => ERC20Contract::class,
        'weth' => WETHContract::class,
        'erc721' => ERC721Contract::class,
    ];

    public static function make(string $key, ConnectionInterface $credentials): ContractService
    {
        if (array_key_exists($key, self::ContractClasses)) {
            $class = self::ContractClasses[$key];
            return new $class($credentials);
        }
        throw new InvalidContractInstanceKeyException();
    }
}

