<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Exceptions\TransactionException;
use Igor360\NftEthPhpConnector\Resources\Resource;
use Igor360\NftEthPhpConnector\Resources\TransactionResource;

class TransactionService extends ResourceService
{
    protected ?Resource $decodeResource;

    public function __construct(TransactionResource $resource)
    {
        $this->setResource($resource);
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
}
