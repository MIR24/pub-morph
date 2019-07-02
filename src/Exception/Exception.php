<?php
namespace MIR24\Morph\Exception;

use MIR24\Morph\Config\Constants;

class Exception
{
    /*
     * Throw exception with additionl message
     * */
    public static function throw ($msg) {
        throw new \Exception(Constants::EXCEPTION_MSG_START . PHP_EOL . $msg);
    }
}
?>