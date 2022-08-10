<?php

namespace Igor360\NftEthPhpConnector\Exceptions;

final class InvalidImplementationClassException extends \Exception
{
    protected $message = ERROR_MESSAGES::INVALID_IMPLEMENTATION;
}
