<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Contracts;

use Exception;
use Igor360\NftEthPhpConnector\Configs\ConfigFacade as Config;
use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Igor360\NftEthPhpConnector\Interfaces\ConfigInterface;
use Igor360\NftEthPhpConnector\Services\ContractService;
use Illuminate\Support\Arr;

class ERC1155Contract extends ContractService
{
    function abi(): array
    {
        return json_decode(Config::get(ConfigInterface::BASE_KEY . ".erc1155ABI"), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Create transfer tokens transaction
     *
     * @param string $from
     * @param string $to
     * @param int $tokenId
     *
     * @return string
     * @throws Exception
     */
    public function encodeTransferToken(string $from, string $to, int $tokenId): string
    {
        $this->validateAddress($from);
        $this->validateAddress($to);
        return $this->ABIService->encodeCall('transferFrom', [$from, $to, $tokenId]);
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $tokenId
     *
     * @return string
     * @throws Exception
     */
    public function encodeSafeTransferFrom(string $from, string $to, int $tokenId): string
    {
        $this->validateAddress($from);
        $this->validateAddress($to);
        return $this->ABIService->encodeCall('safeTransferFrom', [$from, $to, $tokenId]);
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $tokenId
     *
     * @return string
     * @throws Exception
     */
    public function encodeMint(string $to, int $tokenId, int $amount, string $data = ""): string
    {
        $this->validateAddress($to);
        return $this->ABIService->encodeCall('mint', [$to, $tokenId, $amount, $data]);
    }

    public function encodeBurn(string $to, int $tokenId, int $amount): string
    {
        $this->validateAddress($to);
        return $this->ABIService->encodeCall("burn", [$to, $tokenId, $amount]);
    }

    /**
     * Approve tokens
     *
     * @param string $from
     * @param int $tokenId
     *
     * @return string
     * @throws Exception
     */
    public function approve(string $from, int $tokenId): string
    {
        $this->validateAddress($from);
        return $this->ABIService->encodeCall('approve', [$from, $tokenId]);
    }

    /**
     * Return approved address
     *
     * @param string $contractAddress
     * @param int $tokenId
     *
     * @return string
     */
    public function getApproved(string $contractAddress, int $tokenId): string
    {
        $res = $this->callContractFunction($contractAddress, 'getApproved', [$tokenId]);
        return Arr::get($res, 'result');
    }

    public function balanceOf(string $contractAddress, string $address)
    {
        $this->validateAddress($contractAddress, $address);
        $res = $this->callContractFunction($contractAddress, "balanceOf", [$address]);
        return sprintf("%0.0f", hexdec(Arr::first($res)));
    }

    public function baseURI(string $contractAddress)
    {
        $this->validateAddress($contractAddress);
        $res = $this->callContractFunction($contractAddress, "baseURI");
        return Arr::first($res);
    }

    public function name(string $contractAddress)
    {
        $this->validateAddress($contractAddress);
        $res = $this->callContractFunction($contractAddress, "name");
        return Arr::first($res);
    }

    public function symbol(string $contractAddress)
    {
        $this->validateAddress($contractAddress);
        $res = $this->callContractFunction($contractAddress, "symbol");
        return Arr::first($res);
    }
}
