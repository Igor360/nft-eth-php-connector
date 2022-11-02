<?php
return [
    "erc20ABI" => \Igor360\NftEthPhpConnector\Configs\ConfigFacade::loadERC20ABI(),
    "erc721ABI" => \Igor360\NftEthPhpConnector\Configs\ConfigFacade::loadERC721ABI(),
    "erc1155ABI" => \Igor360\NftEthPhpConnector\Configs\ConfigFacade::loadERC1155V2ABI(),
    "wethABI" =>  \Igor360\NftEthPhpConnector\Configs\ConfigFacade::loadWETHABI(),
    "eth" => [
        "host" => "",
        "port" => "",
        "ssh" => false
    ]
];
