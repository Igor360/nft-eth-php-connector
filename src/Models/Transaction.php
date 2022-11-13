<?php

namespace Igor360\NftEthPhpConnector\Models;

use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;

class Transaction extends Model
{
    public ?string $hash;

    public ?bool $status;

    public ?string $from;

    public ?string $to;

    public string $value;

    public string $block;

    public string $blockHash;

    public string $nonce;

    public string $data;

    public string $gas;

    public string $gasPrice;

    public ?string $gasUsed;

    public ?string $cumulativeGasUsed;

    public ?string $contractAddress;

    public ?string $type;

    public ?string $transactionIndex;

    public string $r;

    public string $s;

    public string $v;

    public ?string $logsBloom;

    public ?array $logs;

    public ?string $coin;

    public ?ContractCallInfo $callInfo;
}

