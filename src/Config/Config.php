<?php
namespace MIR24\Morph\Config;

use MIR24\Morph\Config\Constants;
use MIR24\Morph\Exception\Exception;

class Config
{
    // Config file prefix
    protected static $prefix = Constants::CONFIG_NAME . '.';

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