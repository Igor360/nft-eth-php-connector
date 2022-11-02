<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

final class ContractException extends \Exception
{
    protected $message = ERROR_MESSAGES::CONTRACT;
}
