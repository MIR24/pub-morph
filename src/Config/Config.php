<?php
namespace MIR24\Morph;

use MIR24\Morph\Exception\Exception;

class Config
{
    // Config file prefix
    protected static $prefix = 'morph-lib.';

    /*
     * Returns config result
     * */
    public static function get ($sufix) {
        $config = config(self::$prefix.$sufix);

        if ($config !== NULL) {
            return $config;
        } else {
            Exception::throw('Configuration returns NULL for: '.self::$prefix.$sufix);
        }
    }
}
?>