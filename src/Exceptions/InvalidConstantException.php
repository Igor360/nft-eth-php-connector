<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

final class InvalidConstantException extends \Exception
{
    protected $message = ERROR_MESSAGES::INVALID_CONSTANT_NAME;
}
