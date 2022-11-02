<?php

namespace Igor360\NftEthPhpConnector\Exceptions;

final class BadRequestException extends \Exception
{
    protected $message = ERROR_MESSAGES::BAD_REQUEST;
}
