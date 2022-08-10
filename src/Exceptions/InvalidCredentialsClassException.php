<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

final class InvalidCredentialsClassException extends \Exception
{
    protected $message = ERROR_MESSAGES::INVALID_CREDENTIALS_CLASS;
}
