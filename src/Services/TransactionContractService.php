<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Exceptions\ContractException;
use Igor360\NftEthPhpConnector\Exceptions\TransactionException;
use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;
use Igor360\NftEthPhpConnector\Models\ContractCallInfo;
use Igor360\NftEthPhpConnector\Models\Transaction;
use Igor360\NftEthPhpConnector\Resources\Resource;
use Illuminate\Support\Arr;

class TransactionContractService extends TransactionService
{

    public function __construct(Resource $resource)
    {
        parent::__construct($resource);
        $this->validate();
        $this->decode();
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
        $txIds = array_keys($this->getResourceService()->getMethodSelectors() ?? []);
        if (!in_array($txId, $txIds, true)) {
            throw new ContractException("It's not current abi contract transaction");
        }
    }

    public function getMethodType(): ?string
    {
        $constants = $this->getResourceService()->getMethodSelectors() ?? [];
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
        return $this->getResourceService()->getEventsTopics();
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
            $decodedLogs[] = $this->getResourceService()->decodeContractTransactionLogs($topicId, $log);
        }
        $this->getTransactionModel()->callInfo->decodedLogs = $decodedLogs;
    }

    public function decodeTransactionArgs(): void
    {
        $methodId = $this->getContractFunctionId();
        $methods = $this->getResourceService()->getMethodSelectors();
        if (!Arr::has($methods, $methodId)) {
            throw new TransactionException("Invalid method, method with selector ${methodId} not located in abi");
        }
        $functionName = $methods[$methodId] ?? null;
        $this->getTransactionModel()->callInfo->function = $functionName;
        $this->getTransactionModel()->callInfo->decodedArgs = $this->getResourceService()->decodeFunctionArgsWithFunctionId($methodId, $this->getTransactionModel()->data);
    }
}
