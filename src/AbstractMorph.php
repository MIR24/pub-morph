<?php
namespace MIR24\Morph;

use Sunra\PhpSimple\HtmlDomParser;

use MIR24\Morph\Helpers\BracketsHelper;

abstract class AbstractMorph
{
    protected $parser;
    protected $component;

    function __construct ($publicationSource) {
        $this->parser = HtmlDomParser::str_get_html($this->processLoadingHtmlString($publicationSource));

        return $this;
    }

    function __destruct () {
        $this->parser->clear();
    }

    /*
     * Helper function for processing html string before loading
     * */
    private function processLoadingHtmlString ($str) {
        return BracketsHelper::load($str);
    }

    /*
     * Clear memory and load new string
     * */
    public function setHtmlString ($str) {
        $this->parser->load($this->processLoadingHtmlString($str));
    }

    /*
     * Returns html string, depending of decoding attribute
     * */
    public function getHtmlString () {
        return BracketsHelper::unload($this->parser->save());
    }

}
?>