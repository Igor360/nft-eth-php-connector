<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

/**
 * @class ContractABIException
 * @description Exception in processing contract data using abi file
 */
final class ContractABIException extends \Exception
{
    protected $message = ERROR_MESSAGES::ABI;
}
