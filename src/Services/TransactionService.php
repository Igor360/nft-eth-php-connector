<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Exceptions\TransactionException;
use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;
use Igor360\NftEthPhpConnector\Models\ContractCallInfo;
use Igor360\NftEthPhpConnector\Models\Transaction;
use Igor360\NftEthPhpConnector\Resources\Resource;
use Igor360\NftEthPhpConnector\Resources\TransactionResource;
use Web3p\EthereumUtil\Util;

class TransactionService extends ResourceService
{
    protected ?Resource $decodeResource;

    public function __construct(Resource $resource)
    {
        $this->setResource($resource);
    }

    /**
     * @return EthereumService|TokenService|ContractService|null
     */
    public function getResourceService()
    {
        return $this->getResource()->getService();
    }

    public function getTransactionModel()
    {
        /**
         * @var ModelInterface|Transaction
         */
        return $this->getResource()->getModel();
    }

    public function loadTransactionInstance(): self
    {
        $this->getResource()->load();
        return $this;
    }

    public function load(): \Igor360\NftEthPhpConnector\Interfaces\ServiceInterface
    {
        $this->getResource()->load();
        return $this;
    }

    /**
     * @return object
     */
    public function getTransactionInfo(): object
    {
        return $this->getResource()->data()->toObject();
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
     * @return Resource|null
     */
    public function getDecodeResource(): ?Resource
    {
        return $this->decodeResource;
    }

    /**
     * @param Resource|null $decodeResource
     * @return TransactionService
     */
    public function setDecodeResource(?Resource $decodeResource): self
    {
        $this->decodeResource = $decodeResource;
        return $this;
    }

    /**
     * @return string
     * @throws \JsonException
     */
    public function getTransactionInfoJson(): string
    {
        return $this->getResource()->data()->toJson();
    }

    protected function isContract(): void
    {
        if (!is_null($this->getResource()->getAddressOrHash()) && is_null($this->getResource()->data()->data)) {
            throw new TransactionException("It's not contract transaction");
        }
    }

    public function __call($name, $arguments)
    {
        if (str_contains($name, 'contract')) {
            $functionName = str_replace('contract', '', $name);
            $functionName = lcfirst($functionName);
            $contractInstance = $this->getResource()->getService();
            if (method_exists($contractInstance, $functionName)) {
                return $contractInstance->$functionName(...$arguments);
            }
        }

        return $name(...$arguments);
    }

    protected function addressFromPrivate(string $privateKey): string
    {
        $utils = new Util();
        $publicKey = $utils->privateKeyToPublicKey($privateKey);
        return $utils->publicKeyToAddress($publicKey);
    }
}
