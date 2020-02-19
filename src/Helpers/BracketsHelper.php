<?php
namespace MIR24\Morph\Helpers;

use MIR24\Morph\Config\Config;

class BracketsHelper {

    /*
     * Correctly decoding text base on config
     * */
    static function load ($text) {
        if (Config::get('decoded')) {
            return htmlspecialchars_decode($text);
        } else {
            return $text;
        }
    }

    /*
     * Correctly encoding text base on config
     * */
    static function unload ($text) {
        if (Config::get('decoded')) {
            return preg_replace_callback(
                Config::get('regex.removeBrackets'),
                function ($matches) {
                    return '{' . htmlspecialchars($matches[Config::get('regex.preg_match_number')]) . '}';
                },
                $text
            );
        } else {
            return $text;
        }
    }

}
?>