<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

/**
 * @class InvalidMethodCallException
 * @description Error for invalid call method
 */
final class InvalidMethodCallException extends \Exception
{
    protected $message = ERROR_MESSAGES::INVALID_CALL;
}
