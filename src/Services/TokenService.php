<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Contracts\ERC20Contract;
use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Igor360\NftEthPhpConnector\Interfaces\ServiceInterface;
use Igor360\NftEthPhpConnector\Resources\ERC20Resource;

class TokenService extends ResourceService
{

    public function __construct(ERC20Resource $resource)
    {
        $this->setResource($resource);
    }

    /**
     * @return string|null
     */
    public function getContractAddress(): ?string
    {
        return $this->getResource()->getAddressOrHash();
    }

    /**
     * @return ERC20Contract|ContractService|EthereumService
     */
    public function getContract()
    {
        return $this->getResource()->getService();
    }

    /**
     * @param string|null $contractAddress
     * @return TokenService
     */
    public function setContractAddress(string $contractAddress): self
    {
        $this->getResource()->setAddressOrHash($contractAddress);
        return $this;
    }

    public function getTokenInfo(): object
    {
        return $this->getResource()->data()->toObject();
    }


    public function load(): ServiceInterface
    {
        if (!is_null($this->getContractAddress())) {
            $this->getResource()->load();
            return $this;
        }
        throw new InvalidAddressException("Contract address is null");
    }

    /**
     * @throws \JsonException
     */
    public function getTokenInfoJson(): string
    {
        return $this->getResource()->data()->toJson();
    }

    public function getBalance(string $address): string
    {
        return $this->getContract()->balanceOfString($this->getContractAddress(), $address);
    }

    public function getAllowance(string $owner, string $spender): ?string
    {
        return $this->getContract()->allowance($this->getContractAddress(), $owner, $spender);
    }

    public function getFunctionSelector(string $name): array
    {
        return $this->getContract()->getMethodSelector($name);
    }

    public function getEventsTopics(): array
    {
        return $this->getContract()->getEventsTopics() ?? [];
    }
}
