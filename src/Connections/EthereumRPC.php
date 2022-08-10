<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Connections;

use Igor360\NftEthPhpConnector\Exceptions\GethException;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;
use Igor360\NftEthPhpConnector\Services\BaseRPCService;
use Igor360\NftEthPhpConnector\Utils\GettersToFields;
use Illuminate\Support\Arr;

abstract class EthereumRPC extends BaseRPCService
{
    use GettersToFields;

    public const RPC_VERSION = "2.0";

    public function getHost(): string
    {
        return $this->credentials->host();
    }

    public function getPort(): ?int
    {
        return $this->credentials->port();
    }

    public function getSsl(): bool
    {
        return $this->credentials->ssl();
    }

    /**
     * EthereumRPC constructor.
     */
    public function __construct(ConnectionInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @param null|string $endPoint
     * @return string
     */
    private function url(?string $endPoint = null): string
    {
        $protocol = $this->ssl ? "https" : "http";

        if ($this->port) {
            return sprintf('%s://%s:%s%s', $protocol, $this->host, $this->port, $endPoint);
        }

        return sprintf('%s://%s%s', $protocol, $this->host, $endPoint);
    }

    public function baseUrl(): string
    {
        return $this->url();
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

    public function jsonRPC(
        string  $command,
        ?string $endpoint = null,
        ?array  $params = null,
                $functionName = ''
    ): array
    {

        // Prepare JSON RPC Call
        $id = sprintf('%s_%d', $command, time());

        $data = [
            "jsonrpc" => self::RPC_VERSION,
            "id" => $id,
            "method" => $command,
            "params" => $params ?? []
        ];

        // Send JSON RPC Request to Bitcoin daemon
        $response = $this->sendNodeRequest($data, null, true, false, $functionName);

        // Cross-check response ID with request ID
        if (Arr::get($response, "id") !== $id) {
            throw new GethException('Response does not belong to sent request');
        }

        // Check for Error
        $error = Arr::get($response, "error");
        if (is_array($error)) {
            $errorCode = (int)($error["code"] ?? 0);
            $errorMessage = $error["message"] ?? 'An error occurred';
            throw new GethException($errorMessage, $errorCode);
        }

        // Result
        if (!Arr::has($response, "result")) {
            throw new GethException('No response was received');
        }

        return $response;
    }
}

