<?php

namespace Igor360\NftEthPhpConnector\Transactions;

use Igor360\NftEthPhpConnector\Services\TransactionService;
use Web3p\EthereumUtil\Util;

class ERC1155Transaction extends TransactionService
{
    public function burn(string $to, int $id, int $amount, string $privateKey): string
    {
        $data = $this->contractEncodeBurn($to, $id, $amount);
        $addressFrom = $this->addressFromPrivate($privateKey);
        ['transaction' => $transaction] = $this->getResourceService()->prepareTransaction($addressFrom, $this->getResource()->getModel()->address, 0, $data);
        return $this->getResourceService()->signAndBroadcastTransaction($transaction, $privateKey);
    }

    public function mint(string $to, int $id, int $amount, string $privateKey, string $data = "0x00"): string
    {
        $txData = $this->contractEncodeMint($to, $id, $amount, $data);
        $addressFrom = $this->addressFromPrivate($privateKey);
        ['transaction' => $transaction] = $this->getResourceService()->prepareTransaction($addressFrom, $this->getResource()->getModel()->address, 0, $txData);
        return $this->getResourceService()->signAndBroadcastTransaction($transaction, $privateKey);
    }
}