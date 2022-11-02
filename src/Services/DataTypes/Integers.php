<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services\DataTypes;

/**
 * @source https://github.com/digitaldonkey/ethereum-php/tree/master/src/DataType
 */
final class Integers
{

    public static function Unpack(string $hex)
    {
        if (substr($hex, 0, 2) === "0x") {
            $hex = substr($hex, 2);
        }

        if (!$hex) {
            return null;
        }

        $hex = self::HexitPads($hex);
        return gmp_strval(gmp_init($hex, 16), 10);
    }

    public static function HexitPads(string $hex): string
    {
        if (strlen($hex) % 2 !== 0) {
            $hex = "0".$hex;
        }

        return $hex;
    }

    public static function Pack_UInt_LE($dec): string
    {
        $dec = self::checkValidInt($dec);
        return self::HexitPads(bin2hex(gmp_export(gmp_init($dec, 10), 1, GMP_LSW_FIRST | GMP_NATIVE_ENDIAN)));
    }

    public static function Pack_UInt_BE($dec): string
    {
        $dec = self::checkValidInt($dec);
        return self::HexitPads(bin2hex(gmp_export(gmp_init($dec, 10), 1, GMP_MSW_FIRST | GMP_NATIVE_ENDIAN)));
    }

    public static function checkValidInt($dec)
    {
        if (!is_int($dec)) {
            if (!is_string($dec) || !preg_match('/^-?(0|[1-9]+[0-9]*)$/', $dec)) {
                throw new \InvalidArgumentException('Argument must be a valid INT');
            }
        }

        return $dec;
    }
}
