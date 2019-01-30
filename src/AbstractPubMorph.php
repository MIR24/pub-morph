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
    #------------------------ DOM Helpers start --------------------------------
    /*
     * Returns html string, dependig of decoding attribute
     * */
    abstract public function getHtmlString();

    /*
     * Return parser plaintext
     * */
    abstract public function getPlainText();

    /*
     * Remove parent node if parent node is empty
     * */
    abstract protected function removeParentNodeIfEmpty($node);
    #------------------------ DOM Helpers end ----------------------------------
    #----------------------- Incut start ---------------------------------------
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
    abstract public function makeIncutInactive(int $incutId, string $incutTitle = NULL);

    /*
     * Getting incut html tags ids attribute
     * */
    abstract public function getIncutIds();

    /*
     * Search for DOM node inside pub text by attribute tag, attribute
     * and attribute value
     * */
    abstract protected function findIncutsById(int $incutId);

    /*
     * Search for DOM node inside pub text by attribute tag and attribute value
     * */
    abstract protected function findIncuts();
    #----------------------- Incut end -----------------------------------------
    #----------------------- Banner places start -------------------------------
    /*
     * Insert banner spot in text after character count
     * */
    abstract public function insertBannerInTextAfter(int $countLimit, int $pNum, string $content = NULL);
    #----------------------- Banner places end ---------------------------------
}
?>