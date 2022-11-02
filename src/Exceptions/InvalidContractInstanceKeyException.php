<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

/**
 * Invalid key call in contract factory
 */
final class InvalidContractInstanceKeyException extends \Exception
{
    protected $message = ERROR_MESSAGES::INVALID_CONTRACT_KEY;
}
