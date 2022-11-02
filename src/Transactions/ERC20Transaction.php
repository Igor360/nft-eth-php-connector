<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Transactions;

use Igor360\NftEthPhpConnector\Services\TransactionService;

class ERC20Transaction extends TransactionService
{

    public function transfer(string $to, int $amount, string $privateKey): string
    {
        $data = $this->contractEncodeTransfer($to, $amount);
        $addressFrom = $this->addressFromPrivate($privateKey);
        ['transaction' => $transaction] = $this->getResourceService()->prepareTransaction($addressFrom, $this->getResource()->getModel()->address, 0, $data);
        return $this->getResourceService()->signAndBroadcastTransaction($transaction, $privateKey);
    }

    public function transferFrom(string $from, string $to, int $amount, string $privateKey): string
    {
        $data = $this->contractEncodeTransferFrom($from, $to, $amount);
        $addressFrom = $this->addressFromPrivate($privateKey);
        ['transaction' => $transaction] = $this->getResourceService()->prepareTransaction($addressFrom, $this->getResource()->getModel()->address, 0, $data);
        return $this->getResourceService()->signAndBroadcastTransaction($transaction, $privateKey);
    }

    public function approve(string $to, int $amount, string $privateKey): string
    {
        $data = $this->contractEncodeTransfer($to, $amount);
        $addressFrom = $this->addressFromPrivate($privateKey);
        ['transaction' => $transaction] = $this->getResourceService()->prepareTransaction($addressFrom, $this->getResource()->getModel()->address, 0, $data);
        return $this->getResourceService()->signAndBroadcastTransaction($transaction, $privateKey);
    }

    public function burn(string $to, int $amount, string $privateKey): string
    {
        $data = $this->contractEncodeBurn($to, $amount);
        $addressFrom = $this->addressFromPrivate($privateKey);
        ['transaction' => $transaction] = $this->getResourceService()->prepareTransaction($addressFrom, $this->getResource()->getModel()->address, 0, $data);
        return $this->getResourceService()->signAndBroadcastTransaction($transaction, $privateKey);
    }

    public function mint(string $to, int $amount, string $privateKey): string
    {
        $data = $this->contractEncodeMint($to, $amount);
        $addressFrom = $this->addressFromPrivate($privateKey);
        ['transaction' => $transaction] = $this->getResourceService()->prepareTransaction($addressFrom, $this->getResource()->getModel()->address, 0, $data);
        return $this->getResourceService()->signAndBroadcastTransaction($transaction, $privateKey);
    }
}