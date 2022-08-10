<?php

namespace Igor360\NftEthPhpConnector\Models;

use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;

class ContractCallInfo extends Model
{
    public ?string $type;

    public ?string $function;

    public array $decodedArgs;

    public array $decodedLogs;
}
