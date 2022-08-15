<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Exceptions\ContractException;
use Igor360\NftEthPhpConnector\Exceptions\TransactionException;
use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;
use Igor360\NftEthPhpConnector\Models\ContractCallInfo;
use Igor360\NftEthPhpConnector\Models\Transaction;
use Igor360\NftEthPhpConnector\Resources\Resource;
use Illuminate\Support\Arr;
use Web3p\EthereumUtil\Util;

class TransactionTokenService extends TransactionService
{

    public function __construct(Resource $resource)
    {
        parent::__construct($resource);
        $this->validate();
        $this->decode();
    }

    /**
     * @return EthereumService|TokenService|null
     */
    public function getTokenService()
    {
        return $this->getResource()->getService();
    }

    public function getTransactionModel(): Transaction
    {
        /**
         * @var ModelInterface|Transaction
         */
        return $this->getResource()->getModel();
    }

    public function getTransactionLogs(): ContractCallInfo
    {
        return $this->getTransactionModel()->callInfo;
    }

    public function getTransactionLogsJson(): string
    {
        return json_encode($this->getTransactionModel()->callInfo, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string|null $transactionHash
     * @return TransactionTokenService
     */
    public function setTransactionHash(?string $transactionHash): self
    {
        $this->getResource()->setAddressOrHash($transactionHash);
        return $this;
    }

    public function getContractFunctionId(): string
    {
        return substr($this->getResource()->data()->data, 0, 10);
    }

    public function validate(): void
    {
        $this->isContract();
        $txId = $this->getContractFunctionId();
        $txIds = array_keys($this->getTokenService()->getMethodSelectors() ?? []);
        if (!in_array($txId, $txIds, true)) {
            throw new ContractException("It's not token transaction");
        }
    }

    public function getMethodType(): ?string
    {
        $constants = $this->getTokenService()->getMethodSelectors() ?? [];
        return Arr::first(array_values(Arr::where($constants, fn($value, $key) => $key === $this->getContractFunctionId())));
    }

    public function decode(): void
    {
        $this->getTransactionModel()->callInfo = new ContractCallInfo();
        $this->getTransactionModel()->callInfo->type = ucfirst($this->getMethodType());
        $this->decodeTransactionArgs();
        $this->decodeTransactionLogs();
    }

    public function getEventsTopics(): array
    {
        return $this->getTokenService()->getEventsTopics();
    }

    public function decodeTransactionLogs(): void
    {
        $decodedLogs = [];
        $logs = $this->getTransactionModel()->logs ?? [];
        foreach ($logs as $log) {
            $topicId = $log["topics"][0] ?? null;
            if (is_null($topicId)) {
                continue;
            }
            $decodedLogs[] = $this->getTokenService()->decodeContractTransactionLogs($topicId, $log);
        }
        $this->getTransactionModel()->callInfo->decodedLogs = $decodedLogs;
    }

    public function decodeTransactionArgs(): void
    {
        $methodId = $this->getContractFunctionId();
        $methods = $this->getTokenService()->getMethodSelectors();
        if (!Arr::has($methods, $methodId)) {
            throw new TransactionException("Invalid method, method with selector ${methodId} not located in abi");
        }
        $functionName = $methods[$methodId] ?? null;
        $this->getTransactionModel()->callInfo->function = $functionName;
        $this->getTransactionModel()->callInfo->decodedArgs = $this->getTokenService()->decodeContractTransactionArgs($functionName, $this->getTransactionModel()->data);
    }

    public function transfer(string $to, int $amount, string $privateKey): string
    {
        $utils = new Util();
        $data = $this->contractEncodeTransfer($to, $amount);
        $addressFrom = $utils->privateKeyToPublicKey($privateKey);
        ['transaction' => $transaction] = $this->getTokenService()->prepareTransaction($addressFrom, $this->getResource()->model()->address, 0, $data);
        return $this->getTokenService()->signAndBroadcastTransaction($transaction, $privateKey);
    }
}
