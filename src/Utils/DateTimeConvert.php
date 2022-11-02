<?php

namespace Igor360\NftEthPhpConnector\Utils;

use Igor360\UniswapV2Connector\Configs\ConfigFacade;

trait DateTimeConvert
{
    public function toDateTimeZone($timestamp): string
    {
        $date = new \DateTime('now', new \DateTimeZone(ConfigFacade::getConstant('DATE_ZONE')));
        $date->setTimestamp($timestamp);
        return $date->format(ConfigFacade::getConstant('DATE_FORMAT'));
    }
}
