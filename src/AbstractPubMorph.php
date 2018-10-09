<?php
/*
 * Morphes publication text by predefined set of rules.
 * E.g. remove node from publication:
 *
 * $publication = "<div>Some text. <div incut-id='42'>Incut text.</div></div>";
 * $incutRemoved = \MIR24\PubMorph\Morpher($publication)->removeIncut(42);
 * */
namespace MIR24\Morph;

use Sunra\PhpSimple\HtmlDomParser;

abstract class AbstractPubMorph extends HtmlDomParser 
{
    private $pubSource;
    
    function __construct($publicationSource){
        $this->pubSource = $publicationSource;
        return $this;
    }

    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * removes node from publication, than returns publication text morphed.
     * */ 
    abstract public function removeIncut(string $nodeAttrName, int $nodeAttrValue);

    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * fillup node with a specific content, than returns publication text morphed.
     * */ 
    abstract public function placeIncut(string $nodeAttrName, int $nodeAttrValue, string $content);
}
