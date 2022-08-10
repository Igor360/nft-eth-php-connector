<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

final class TransactionException extends \Exception
{
    protected $message = ERROR_MESSAGES::TRANSACTION;
}