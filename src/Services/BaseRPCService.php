<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Exceptions\BadRequestException;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;
use Igor360\NftEthPhpConnector\Interfaces\RPCInterface;

/**
 * Class BaseRPCService
 * @package App\Services\Debug\Net
 */
abstract class BaseRPCService implements RPCInterface
{

    protected ConnectionInterface $credentials;

    /**
     * @var \CurlHandle|resource
     */
    protected $curl;

    /**
     * @param null $method
     * @param null $data
     * @param false $need_encode
     * @param false $is_put
     * @return array
     * @throws \JsonException
     */
    public function sendNodeRequest($data = null, $method = null, $need_encode = true, $is_put = false, $functionName = ''): array
    {
        $headers = $this->baseHeaders();
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        if ($data) {
            if ($is_put) {
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
            }
            if ($need_encode) {
                $data = json_encode($data, JSON_THROW_ON_ERROR);
            }
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($this->curl, CURLOPT_POST, 1);
        }
        $url = $this->baseUrl() . (is_null($method) ? "" : "/$method");
        curl_setopt($this->curl, CURLOPT_URL, $url);

        $res = curl_exec($this->curl);
        if (is_string($res)) {
            try {
                return json_decode($res, true, 512, JSON_THROW_ON_ERROR);
            } catch (\Exception $e) {
                return [];
            }

        }
        throw new BadRequestException("Something wend wrong! Curl error : ${res}");
    }

    public function baseUrl(): string
    {
        return $this->host() . (is_null($this->port()) ? "" : ":" . $this->port()) . (is_null(
                $this->apiVersion()
            ) ? "" : "/" . $this->apiVersion());
    }

    public function baseHeaders(): array
    {
        return [
            "Cache-Control: no-cache",
            "Content-Type: application/json;charset=utf-8",
            "accept: application/json;charset=utf-8"
        ];
    }


    public function apiVersion(): ?string
    {
        return null; /// Default value always null
    }

    /**
     * @return ConnectionInterface
     */
    public function getCredentials(): ConnectionInterface
    {
        return $this->credentials;
    }
}
