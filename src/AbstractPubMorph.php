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
    protected $decode;
    
    function __construct($publicationSource, $decode = true) {
        $this->decode = $decode;
        if ($decode) {
            $this->parser = HtmlDomParser::str_get_html(htmlspecialchars_decode($publicationSource));
        } else {
            $this->parser = HtmlDomParser::str_get_html($publicationSource);
        }
        return $this;
    }

    function __destruct() {
        $this->parser->clear();
    }

    /*
     * Returns html string, dependig of decoding attribute
     * */ 
    abstract public function getHtmlString();

    /*
     * Removes node from publication, than returns publication text morphed.
     * */
    abstract public function removeIncut(int $incutId);

    /*
     * Fillup node with a specific content, than returns publication text morphed.
     * */ 
    abstract public function replaceIncut(int $incutId, string $content);

    /*
     * Fillup node with a specific content, making incut interactive, than 
     * returns publication text morphed.
     * */ 
    public function makeIncutInactive(int $incutId, string $incutTitle);

    /*
     * Getting incut html tags ids attribute
     * */ 
    public function getIncutIds();
}
