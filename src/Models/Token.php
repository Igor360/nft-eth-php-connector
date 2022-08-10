<?php

namespace Igor360\NftEthPhpConnector\Models;

use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;

class Token extends Model
{
    public ?string $address;

    public string $name;

    public string $symbol;

    public string $decimals;

    public string $totalSupply;

    public ?string $owner; // It's not required field can be null, not all contract to realize this functionality

    public function mapFrom(): array
    {
        return [
            'name' => 'name',
            'symbol' => 'symbol',
            'decimals' => 'decimals',
            'totalSupply' => 'totalSupply',
            'owner' => 'owner'
        ];
    }


}
