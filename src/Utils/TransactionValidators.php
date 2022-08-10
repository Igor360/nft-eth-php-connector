<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Utils;

use Igor360\UniswapV2Connector\Services\EthereumService;

/**
 * @class TransactionValidators
 * @description Transaction helpers validation functions
 */
trait TransactionValidators
{
    public static function isFail(EthereumService $instance, $tx): bool
    {
        if (is_null($tx)) {
            return false;
        }
        $transaction = $instance->getTransactionReceipt($tx['hash'] ?? $tx->hash);
        return $transaction && $transaction['status'] === '0x0';
    }

}
