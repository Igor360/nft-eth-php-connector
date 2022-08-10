<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

final class GethException extends \Exception
{
    protected $message = ERROR_MESSAGES::GETH;
}
