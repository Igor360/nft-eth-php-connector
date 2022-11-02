<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

/**
 * @class InvalidAddressException
 * @description Exception for invalid address format
 */
final class InvalidAddressException extends \Exception
{
    protected $message = ERROR_MESSAGES::INVALID_ADDRESS;
}
