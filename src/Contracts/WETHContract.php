<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Contracts;


use Igor360\NftEthPhpConnector\Configs\ConfigFacade as Config;

class WETHContract extends ERC20Contract
{
    function abi(): array
    {
        return json_decode(Config::get("uniswap-v2-connector.wethABI"), true, 512, JSON_THROW_ON_ERROR);
    }

}
