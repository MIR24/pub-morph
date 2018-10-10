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

abstract class AbstractPubMorph
{
    protected $parser;
    
    function __construct($publicationSource) {
        $this->parser = HtmlDomParser::str_get_html($publicationSource);
        return $this;
    }

    function __destruct() {
        $this->parser->clear();
    }

    abstract public function getHtmlString();
    abstract public function getHtmlStringWithRegexEncode();
    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * removes node from publication, than returns publication text morphed.
     * */ 
    abstract public function removeIncut(int $incutId);

    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * fillup node with a specific content, than returns publication text morphed.
     * */ 
    abstract public function replaceIncut(int $incutId, string $content);
}
