<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

final class BroadcastingException extends \Exception
{
    protected $message = ERROR_MESSAGES::BROADCAST_ERROR;
}