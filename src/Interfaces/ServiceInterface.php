<?php

namespace Igor360\NftEthPhpConnector\Interfaces;

interface ServiceInterface
{
    public function load(): self;

    public function update(): self;

    public function data(): array;
}