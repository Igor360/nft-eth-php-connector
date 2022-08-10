<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Exceptions;

/**
 * Error messages constants
 */
interface ERROR_MESSAGES
{
    public const BAD_REQUEST = "Invalid request was send";

    public const GETH = "Geth client error response";

    public const INVALID_CALL = "Called field not found, please realize getter in format 'getFieldName()'";

    public const INVALID_ADDRESS = "Invalid address format";

    public const ABI = "Invalid function decode";

    public const INVALID_CONTRACT_KEY = "Invalid key for contract instance";

    public const INVALID_CREDENTIALS_CLASS = "Invalid credentials object instance";

    public const INVALID_IMPLEMENTATION = "Class not implements interface";

    public const STATIC_CALL = "Only call static functions";

    public const INVALID_CONSTANT_NAME = "Getting constant not exist in class";

    public const CONTRACT = "Invalid contract";

    public const TRANSACTION = "Invalid transaction";

    public const INVALID_CLASS_NAME = "Invalid class name for make instance";
}
