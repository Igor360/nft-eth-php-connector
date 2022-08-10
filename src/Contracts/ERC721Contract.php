<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Contracts;

use Exception;
use Igor360\NftEthPhpConnector\Configs\ConfigFacade as Config;
use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Igor360\NftEthPhpConnector\Services\ContractService;
use Illuminate\Support\Arr;

class ERC721Contract extends ContractService
{
    function abi(): array
    {
        return json_decode(Config::get("uniswap-v2-connector.erc721ABI"), true, 512, JSON_THROW_ON_ERROR);
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

    /**
     * @param string $contractAddress
     * @param string $owner
     *
     * @return int
     * @throws InvalidAddressException
     */
    public function balanceOf(string $contractAddress, string $owner)
    {
        $this->validateAddress($owner);

        return $this->callContractFunction($contractAddress, 'balanceOf', [$owner]);
    }
}
