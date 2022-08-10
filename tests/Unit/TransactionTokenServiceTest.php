<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Tests\Unit;

use Igor360\NftEthPhpConnector\Connections\BaseCredentials;
use Igor360\NftEthPhpConnector\Contracts\ContractsFactory;
use Igor360\NftEthPhpConnector\Contracts\ERC20Contract;
use Igor360\NftEthPhpConnector\Resources\TransactionResource;
use Igor360\NftEthPhpConnector\Services\TransactionTokenService;
use Igor360\NftEthPhpConnector\Tests\TestCases;
use Igor360\NftEthPhpConnector\Tests\TestConstantsInterface;

class TransactionTokenServiceTest extends TestCases
{
    public function testNotLoadedTransaction(): void
    {
        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);

        $hash = "0x79774e1753e925ccfcbc3091d5e6c66fa052becb988d3893574340c24fe51d1c";
        $transactionResource = new TransactionResource($hash, $credentials);
        $this->assertEquals('{"hash":"0x79774e1753e925ccfcbc3091d5e6c66fa052becb988d3893574340c24fe51d1c"}', $transactionResource->data()->toJson());
    }

    public function testLoadedTransaction(): void
    {
        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);

        $hash = "0x2547fbda1123d0fcd66caf7d41f70ddf48ad821dee7bd0250a5562bf017247b5";
        $transactionResource = new TransactionResource($hash, $credentials);
        $transactionResource->load();
        $txData = '{"hash":"0x2547fbda1123d0fcd66caf7d41f70ddf48ad821dee7bd0250a5562bf017247b5","status":true,"from":"0x394a64e586fc05bd28783351f14dfcc426efd230","to":"0x7ef95a0fee0dd31b22626fa2e10ee6a223f8a684","value":"0","block":"21830217","blockHash":"0xf9cb5f1a74315883e07df51a5ce38c55325c7728667bfc2c0daaccbf4d50d860","nonce":"604","data":"0xa9059cbb0000000000000000000000005ec3528afe940abff1c7760ca33a5152dc5cd06c0000000000000000000000000000000000000000000000000de0b6b3a7640000","gas":"54393","gasPrice":"10000000000","gasUsed":"36262","cumulativeGasUsed":"682352","contractAddress":null,"type":"0x0","transactionIndex":"7","r":"0x78e3942b7493f4948f75027f83de3aafe62c9cf576428da3b65026d536f002bc","s":"0x5e02cfa0b9a3c7c57b8af380a4710ab860fffe38fce1bf5e469365741920d730","v":"0xe6","logsBloom":"0x00000000000001000000000000000000000000000000000000000000000000000000000000000000010000002000000000000000000000000000000000000000000000000000000000000008000000000000000000000000000000000000000000000000000000000000000000000000000000000000000004000010000000000008000000000000000000000000000000000000000000000000000000000000000000000000000000000002000000000000000000000000000000000000000004000002000000000000000000000000000000000000000000400000000000000000000008000000000000000000000000000000000000000000000000000000","logs":[{"address":"0x7ef95a0fee0dd31b22626fa2e10ee6a223f8a684","topics":["0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef","0x000000000000000000000000394a64e586fc05bd28783351f14dfcc426efd230","0x0000000000000000000000005ec3528afe940abff1c7760ca33a5152dc5cd06c"],"data":"0x0000000000000000000000000000000000000000000000000de0b6b3a7640000","blockNumber":"0x14d1a49","transactionHash":"0x2547fbda1123d0fcd66caf7d41f70ddf48ad821dee7bd0250a5562bf017247b5","transactionIndex":"0x7","blockHash":"0xf9cb5f1a74315883e07df51a5ce38c55325c7728667bfc2c0daaccbf4d50d860","logIndex":"0x7","removed":false}]}';
        $this->assertEquals($txData, $transactionResource->data()->toJson());
    }

    public function testDecodeTransactionLogs(): void
    {
        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);

        $hash = "0x2547fbda1123d0fcd66caf7d41f70ddf48ad821dee7bd0250a5562bf017247b5";
        $transactionResource = new TransactionResource($hash, $credentials);
        $transactionResource->setHandler(ERC20Contract::class);
        $tokenService = new TransactionTokenService($transactionResource);
        $this->assertEquals('{"type":"Transfer","function":"transfer","decodedArgs":{"recipient":"0x5ec3528afe940abff1c7760ca33a5152dc5cd06c","amount":"1000000000000000000"},"decodedLogs":[{"Event":"Transfer","from":"0x394a64e586fc05bd28783351f14dfcc426efd230","to":"0x5ec3528afe940abff1c7760ca33a5152dc5cd06c","value":"1000000000000000000","contract":"0x7ef95a0fee0dd31b22626fa2e10ee6a223f8a684"}]}', $tokenService->getTransactionLogsJson());

    }
}