<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Utils;

trait WeiUtils
{
    public static function convertCurrency(float $amount, string $from = 'wei', string $to = 'ether')
    {

        // relative to Ether
        $convertTabe = [
            'wei' => 1000000000000000000,
            // Kwei, Ada, Femtoether
            'kwei' => 1000000000000000,
            // Mwei, Babbage, Picoether
            'mwei' => 1000000000000,
            // Gwei, Shannon, Nanoether, Nano
            'gwei' => 1000000000,
            // Szabo, Microether,Micro
            'szabo' => 1000000,
            // Finney, Milliether,Milli
            'methere' => 1000,
            'ether' => 1,
            // Kether, Grand,Einstein
            'kether' => 0.001,
            // Kether, Grand,Einstein
            'mether' => 0.000001,
            'gether' => 0.000000001,
            'thether' => 0.000000000001,
        ];
        if (!isset($convertTabe[$from])) {
            throw new \Exception('Inknown currency to convert from "' . $from . '"');
        }
        if (!isset($convertTabe[$to])) {
            throw new \Exception('Inknown currency to convert to "' . $to . '"');
        }
        return $convertTabe[$to] * $amount / $convertTabe[$from];
    }
}
