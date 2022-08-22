<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Configs\ConfigFacade as Config;
use Igor360\NftEthPhpConnector\Connections\EthereumRPC;
use Igor360\NftEthPhpConnector\Exceptions\BroadcastingException;
use Igor360\NftEthPhpConnector\Exceptions\GethException;
use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Illuminate\Support\Arr;
use Web3p\EthereumTx\Transaction;

/**
 * Class EthereumService
 * @package Igor360\UniswapV2Connector\Services
 */
class EthereumService extends EthereumRPC
{

    /**
     * Base ethereum regex
     */
    public const ETHEREUM_ADDRESS = '/^0x[a-fA-F0-9]{40}$/';

    public const ETHEREUM_HASH = '/^0x[a-fA-F0-9]{64}$/';

    /**
     * @param mixed ...$addresses
     * @throws InvalidAddressException
     */
    public
    function validateAddress(
        ...$addresses
    ): void
    {
        foreach ($addresses as &$address) {
            if (!preg_match(self::ETHEREUM_ADDRESS, $address)) {
                throw new InvalidAddressException();
            }
        }
    }

    public function getBalance(string $address): string
    {
        $this->validateAddress($address);
        $balance = $this->jsonRPC('eth_getBalance', null, [$address, "latest"]);
        return (string)hexdec(Arr::get($balance, 'result'));
    }

    public function getTransactionByHash(string $hash): ?array
    {
        $res = $this->jsonRPC('eth_getTransactionByHash', null, [$hash]);
        return Arr::get($res, 'result');
    }

    public function getTxCountForAddress(string $address, string $quantity = "pending"): string
    {
        $this->validateAddress($address);
        $res = $this->jsonRPC('eth_getTransactionCount', null, [$address, $quantity]);
        return Arr::get($res, 'result', '0x0');
    }

    public function getGethGasPrice(): string
    {
        $res = $this->jsonRPC('eth_gasPrice');
        return Arr::get($res, 'result', '0x0');
    }

    public function getCurrentBlockNumber(): int
    {
        $res = $this->jsonRPC('eth_blockNumber');
        return hexdec(Arr::get($res, 'result'));
    }

    public function getBlockTransactions(int $blockNumber, bool $onlyHashes = false): array
    {
        $res = $this->jsonRPC('eth_getBlockByNumber', null, ['0x' . dechex($blockNumber), !$onlyHashes]);
        return Arr::get($res, 'result.transactions');
    }

    public function getBlockTransactionsCountByNumber(int $blockNumber): int
    {
        $res = $this->jsonRPC('eth_getBlockTransactionCountByNumber', null, ['0x' . dechex($blockNumber)]);
        return hexdec(Arr::get($res, 'result'));
    }

    public function getChainId(): int
    {
        $res = $this->jsonRPC('net_version');
        return (int)Arr::get($res, 'result');
    }

    public function getTransactionReceipt(string $txHash): ?array
    {
        $res = $this->jsonRPC("eth_getTransactionReceipt", null, [$txHash]);
        return $res ? Arr::get($res, 'result') : [];
    }

    public
    function signAndBroadcastTransaction(
        Transaction $transaction,
        string      $privateKey
    ): string
    {
        try {
            $hash = $transaction->sign($privateKey);
            var_dump($hash);
            ob_flush();
            return $this->broadcastTransactionHash($hash);
        } catch (GethException $e) {
            if (in_array(
                strtolower($e->getMessage()),
                ['replacement transaction underpriced', 'intrinsic gas too low'],
                true
            )) {
                return $this->handleUnderPricedTransactions($transaction, $privateKey);
            }
            if (in_array(strtolower($e->getMessage()), ['nonce too low', 'already known'], true)) {
                return $this->handleNonceLowTransactions($transaction, $privateKey);
            }

            if (str_contains(strtolower($e->getMessage()), 'exceeds the configured cap')) {
                return $this->handleBigCommissionPriceTransactions($transaction, $privateKey);
            }
            throw $e;
        }
    }

    public
    function signAndBroadcastTransactionByNode(
        Transaction $transaction,
        string      $privateKey
    ): string
    {
        // TODO
        return '';
    }

    protected
    function broadcastTransactionHash(
        string $hash
    ): ?string
    {
        try {
            $res = $this->jsonRPC('eth_sendRawTransaction', null, ["0x${hash}"]);
            return Arr::get($res, 'result', null);
        } catch (GethException $e) {
            throw new BroadcastingException($e->getMessage());
        }
    }

    public function calculateGasLimit(
        string $from, string $to, int $amount, string $data = '0x0', string $block = 'latest'
    ): string
    {
        $this->validateAddress($from, $to);
        $params = [
            'from' => $from,
            'to' => $to,
            'value' => '0x' . dechex($amount),
            'data' => $data,
        ];
        $res = $this->jsonRPC('eth_estimateGas', null, [(object)$params, $block]);
        return Arr::get($res, 'result');
    }

    public function prepareTransaction(string $from, string $to, int $amountInWEI = 0, string $data = '0x00'): array
    {
        $this->validateAddress($from, $to);
        $gasPrice = $this->getGethGasPrice();
        $gasLimit = $this->calculateGasLimit($from, $to, $amountInWEI, $data);
        $nonce = $this->getTxCountForAddress($from);
        $chainId = $this->getChainId();
        $transaction = new Transaction (
            [
                'nonce' => $nonce,
                'gasPrice' => $gasPrice,
                'gasLimit' => $gasLimit,
                'from' => $from,
                'to' => $to,
                'value' => '0x' . dechex($amountInWEI),
                'data' => "0x$data",
                'chainId' => $chainId,
            ]
        );
        return compact('gasLimit', 'gasPrice', 'transaction', 'nonce');
    }

    /**
     * @param $tx
     * @param string|int $block
     * @return string
     * @throws GethException
     */
    public function ethCall($tx, $block = "latest", $functionName = ''): string //0x44b19dfc
    {
        $res = $this->jsonRPC(
            "eth_call",
            null,
            [
                $tx,
                $block
            ],
            $functionName
        );

        return Arr::get($res, "result");
    }

    protected function handleUnderPricedTransactions(Transaction $transaction, string $privateKey): string
    {
        $gasPrice = hexdec($transaction->offsetGet('gasPrice'));
        $gas = hexdec($transaction->offsetGet('gas'));
        $transaction->offsetSet('gas', '0x' . dechex((int)($gas + 0.1 * $gas)));
        $transaction->offsetSet('gasPrice', '0x' . dechex((int)($gasPrice + 0.1 * $gasPrice)));

        return $this->signAndBroadcastTransaction($transaction, $privateKey);
    }

    /**
     * @param Transaction $transaction
     *
     * @return string
     */
    protected function handleBigCommissionPriceTransactions(Transaction $transaction, string $privateKey): string
    {
        $gasPrice = hexdec($transaction->offsetGet('gasPrice'));
        $gas = hexdec($transaction->offsetGet('gas'));
        $transaction->offsetSet('gas', '0x' . dechex((int)($gas - 0.1 * $gas)));
        $transaction->offsetSet('gasPrice', '0x' . dechex((int)($gasPrice - 0.1 * $gasPrice)));

        return $this->signAndBroadcastTransaction($transaction, $privateKey);
    }

    /**
     * @param Transaction $transaction
     *
     * @return string
     */
    protected function handleNonceLowTransactions(Transaction $transaction, string $privateKey): string
    {
        $nonce = hexdec($transaction->offsetGet('nonce'));
        $transaction->offsetSet('nonce', '0x' . dechex($nonce + 1));

        return $this->signAndBroadcastTransaction($transaction, $privateKey);
    }
}

