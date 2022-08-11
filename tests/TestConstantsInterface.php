<?php

namespace Igor360\NftEthPhpConnector\Tests;

interface TestConstantsInterface
{
    public const RPC_HOST = "data-seed-prebsc-1-s1.binance.org";
    public const RPC_PORT = 8545;

    // The following keys uses only for tests, not use them in production. Its key was indexed by GitHub bots and every token on it will be lost
    public const ADDRESS = "0xD950cCDfCb5332748A350031461d92A77C93994A";
    public const KEY = "0x66bae4ed7ef986d8012e050ab3312f3c62aaf6c9c178428c528f297b46cfac57";
}