<?php
namespace MIR24\Morph\Helpers;

class LightboxHelper {

    /*
     * Correctly decoding text base on config
     * */
    static function process ($imgSrc, $lightBoxName, $imageCaption, $content) {
        return '<a href="'.$imgSrc.'" data-lightbox="'.$lightBoxName.'" data-title="'.$imageCaption.'">'.$content.'</a>';
    }

}
?>