<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Contracts;


use Igor360\NftEthPhpConnector\Configs\ConfigFacade as Config;
use Igor360\NftEthPhpConnector\Interfaces\ConfigInterface;

class WETHContract extends ERC20Contract
{
    function abi(): array
    {
        return json_decode(Config::get(ConfigInterface::BASE_KEY . ".wethABI"), true, 512, JSON_THROW_ON_ERROR);
    }

}
