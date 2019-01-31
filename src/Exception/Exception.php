<?php
namespace MIR24\Morph\Exception;

class Exception
{
    // Deafult error start
    protected static $libName = 'MIR24/pub-morph'.PHP_EOL;

    /*
     * Throw exception with additionl message
     * */
    public static function throw ($msg) {
        throw new \Exception(self::$libName.$msg);
    }
}
?>