<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Tests\Unit;

use Igor360\NftEthPhpConnector\Connections\BaseCredentials;
use Igor360\NftEthPhpConnector\Resources\ERC20Resource;
use Igor360\NftEthPhpConnector\Services\TokenService;
use Igor360\NftEthPhpConnector\Tests\TestCases;
use Igor360\NftEthPhpConnector\Tests\TestConstantsInterface;

final class TokenServiceTest extends TestCases
{

    public function testGetTokenInfo(): void
    {

        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);

        $token = "0xaebcd1e0807d0000a382a4d95b9045bbeb4ad795"; // https://bscscan.com/address/0xe9e7cea3dedca5984780bafc599bd69add087d56

        $resource = new ERC20Resource($token, $credentials);

        $service = new TokenService($resource);

        $expectedResponse = '{"address":"0xaebcd1e0807d0000a382a4d95b9045bbeb4ad795","name":"GA*TO","symbol":"GATO","decimals":"18","totalSupply":"100000000000000000000000","owner":null}';
        $this->assertEquals($expectedResponse, $service->getTokenInfoJson());
    }

}