<?php
return [
    "erc20ABI" => \Igor360\UniswapV2Connector\Configs\ConfigFacade::loadERC20ABI(),
    "erc721ABI" => \Igor360\UniswapV2Connector\Configs\ConfigFacade::loadERC721ABI(),
    "erc1155ABI" => \Igor360\UniswapV2Connector\Configs\ConfigFacade::loadERC721ABI(),
    "wethABI" =>  \Igor360\UniswapV2Connector\Configs\ConfigFacade::loadWETHABI(),
    "eth" => [
        "host" => "",
        "port" => "",
        "ssh" => false
    ]
];
