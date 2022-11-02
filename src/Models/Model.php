<?php

namespace Igor360\NftEthPhpConnector\Models;

use Igor360\NftEthPhpConnector\Interfaces\ModelInterface;

abstract class Model implements ModelInterface
{
    public function toObject(): object
    {
        return $this;
    }

    public function toArray(): array
    {
        return (array)$this;
    }

    public function mapFrom(): array
    {
        return [];
    }

    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }

}