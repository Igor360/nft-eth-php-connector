<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Tests\Unit;

use Igor360\NftEthPhpConnector\Connections\BaseCredentials;
use Igor360\NftEthPhpConnector\Services\EthereumService;
use Igor360\NftEthPhpConnector\Tests\TestCases;
use Igor360\NftEthPhpConnector\Tests\TestConstantsInterface;
use Igor360\NftEthPhpConnector\Utils\WeiUtils;
use Web3p\EthereumTx\Transaction;

class EthereumServiceTest extends TestCases
{
    public function testEstimateGasAPI(): void
    {
        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);
        $ethereumService = new EthereumService($credentials);
        $this->assertTrue(hexdec($ethereumService->getGethGasPrice()) > 0);
        $this->assertTrue(hexdec($ethereumService->calculateGasLimit("0x621564D4c278E94bc657631FBbF851DdDAB63184", "0x07b8146Bb7629FAAE2886ecDB6E4e6B7d08eb788", 1)) > 0);
    }

    public function testGetChainIdFromNode(): void
    {
        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);
        $ethereumService = new EthereumService($credentials);
        $this->assertSame($ethereumService->getChainId(), 97);
    }

    public function testGetNonceForAddress(): void
    {
        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);
        $ethereumService = new EthereumService($credentials);
        $this->assertTrue(hexdec($ethereumService->getTxCountForAddress("0x621564D4c278E94bc657631FBbF851DdDAB63184")) > 0);
    }

    public function testPrepareTransaction(): void
    {
        $credentials = new BaseCredentials(TestConstantsInterface::RPC_HOST, TestConstantsInterface::RPC_PORT, true);
        $ethereumService = new EthereumService($credentials);
        $amount = WeiUtils::convertCurrency(0.0001, 'ether', 'wei');
        /**
         * @var $transaction Transaction
         */
        ['transaction' => $transaction] = $ethereumService->prepareTransaction(TestConstantsInterface::ADDRESS, '0x621564D4c278E94bc657631FBbF851DdDAB63184', (int)$amount);
        $hash = $ethereumService->signAndBroadcastTransaction($transaction, TestConstantsInterface::KEY);
        $this->assertNotNull($hash);
        $transactionFromNode = $ethereumService->getTransactionByHash($hash);
        $this->assertNotNull($transactionFromNode);
        $this->assertTrue(mb_strtolower($transaction->offsetGet('from')) === $transactionFromNode['from'] ?? null, "From:" . $transactionFromNode['from'] ?? null);
        $this->assertTrue('0x' . dechex((int)$amount) === $transactionFromNode['value'] ?? null);
    }
}