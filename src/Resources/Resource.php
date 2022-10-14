<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Exceptions\ClassNameException;
use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;
use Igor360\NftEthPhpConnector\Services\ContractService;
use Igor360\NftEthPhpConnector\Services\EthereumService;
use Igor360\NftEthPhpConnector\Services\TokenService;

abstract class Resource
{
    protected ModelInterface $model;

    /**
     * @var EthereumService|TokenService|ContractService
     */
    protected $service;

    protected ?string $addressOrHash;

    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }

    /**
     * @return EthereumService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getAddressOrHash(): ?string
    {
        return $this->addressOrHash;
    }

    /**
     * @param string|null $addressOrHash
     */
    public function setAddressOrHash(?string $addressOrHash): void
    {
        $this->addressOrHash = $addressOrHash;
        $this->validateAddressOrHash();
        $this->initResource();
        if (property_exists($this->model, 'address')) {
            $this->model->address = $addressOrHash;
        }
        if (property_exists($this->model, 'hash')) {
            $this->model->hash = $addressOrHash;
        }
    }

    public function makeMap(): void
    {
        foreach ($this->model->mapFrom() as $modelVariableName => $contractFunctionName) {
            $this->model->$modelVariableName = $this->service->$contractFunctionName($this->addressOrHash);
        }
    }

    protected function initResource(): void
    {
        $className = $this->model();
        if (!is_subclass_of($className, ModelInterface::class)) {
            throw new ClassNameException("Class ${className} is not instance of ModelInterface");
        }
        $this->model = new $className();
    }

    public function data(): ModelInterface
    {
        return $this->model;
    }

    public function setHandler(string $serviceClass): void
    {
        if (is_subclass_of($serviceClass, EthereumService::class)) {
            $credentials = $this->service->getCredentials();
            $this->service = new $serviceClass($credentials);
            return;
        }
        throw new ClassNameException("Invalid handler name");
    }

    abstract public function validateAddressOrHash(): void;

    abstract public function load(): self;

    abstract public function model(): string;
}