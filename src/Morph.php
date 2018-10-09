<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractPubMorph;

class Morph extends AbstractPubMorph
{
    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * removes node from publication, than returns publication text morphed.
     * */ 
    public function removeIncut($nodeAttrValue) {
        return 1;
    }

    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * fillup node with a specific content, than returns publication text morphed.
     * */ 
    public function placeIncut(int $nodeAttrValue, string $content) {
        return 2;
    }
}
