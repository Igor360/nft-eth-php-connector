<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services\DataTypes;

/**
 * @source https://github.com/digitaldonkey/ethereum-php/tree/master/src/DataType
 */
final class ASCII
{

    public static function base16Encode(string $ascii)
    {
        if (is_string($ascii) && strlen($ascii) !== mb_strlen($ascii)) {
            throw new \InvalidArgumentException('Cannot encode UTF-8 string into hexadecimals');
        }

        $hex = "";
        for ($i = 0, $iMax = strlen($ascii); $i < $iMax; $i++) {
            $hex .= str_pad(dechex(ord($ascii[$i])), 2, "0", STR_PAD_LEFT);
        }

        return $hex;
    }

    public static function base16Decode($hex): string
    {
        $data = $hex;
        // Remove "0x" prefix
        if (strpos($data, "0x") === 0) {
            $data = substr($data, 2);
        }

        // Even-out uneven number of hexits
        if (strlen($data) % 2 !== 0) {
            $data = "0" . $data;
        }
        $hex = $data;
        $str = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        $str = trim($str);
        $str = trim($str, "\x00..\x1F"); // удаляем управляющие ASCII-символи

        return $str;
    }
}
