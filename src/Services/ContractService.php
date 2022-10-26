<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;

/**
 * @class ContractService
 * @description Base contract service
 */
abstract class ContractService extends EthereumService
{

    protected ABIService $ABIService;

    protected array $contractABI = [];

    public function abi(): array
    {
        return $this->contractABI;
    }

    protected function abiFromConfig(string $data): array
    {
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $contractABI
     */
    public function setContractABI(array $contractABI): void
    {
        $this->contractABI = $contractABI;
        $this->ABIService = new ABIService($this->abi());
    }

    public function __construct(ConnectionInterface $credentials)
    {
        parent::__construct($credentials);
        $this->ABIService = new ABIService($this->abi());
    }

    public function clientCallContractFunction(
        string $contractAddress,
        string $functionName,
        array  $params = [],
               $block = "latest"
    ): string
    {
        $tx = new \stdClass();
        $tx->to = $contractAddress;
        $tx->data = $this->ABIService->encodeCall($functionName, $params);
        return $this->ethCall($tx, is_int($block) ? '0x' . dechex($block) : $block, $functionName);
    }

    public function callContractFunction(
        string $contractAddress,
        string $functionName,
        array  $params = [],
               $block = "latest"
    )
    {
        $callRes = $this->clientCallContractFunction($contractAddress, $functionName, $params, $block);
        return $this->decodeRespose($functionName, $callRes);
    }

    public function decodeRespose(string $function, $response)
    {
        return $this->ABIService->decodeResponse($function, $response);
    }

    public function getMethodSelector(string $functionName): array
    {
        return $this->ABIService->generateMethodSelector($functionName);
    }

    public function getMethodSelectors(): array
    {
        return $this->ABIService->generateMethodSelectors();
    }

    public function getEventsTopics(): array
    {
        return $this->ABIService->getEventsTopics() ?? [];
    }

    public function decodeContractTransactionLogs(string $topicId, array $encodedLogs): ?array
    {
        return $this->ABIService->decodeEventParams($topicId, $encodedLogs);
    }

    public function decodeContractTransactionArgs(string $function, string $encoded): array
    {
        return $this->ABIService->decodeFunctionArgs($function, $encoded);
    }

    public function decodeContractTransactionArgsByFunctionId(string $functionId, string $encoded): array
    {
        return $this->ABIService->decodeFunctionArgsWithFunctionId($functionId, $encoded);
    }
}

