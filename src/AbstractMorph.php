<?php
namespace MIR24\Morph;

use Sunra\PhpSimple\HtmlDomParser;

use MIR24\Morph\Helpers\BracketsHelper;

abstract class AbstractMorph
{
    protected $parser;
    protected $component;

    function __construct($publicationSource) {
        $this->parser = HtmlDomParser::str_get_html(BracketsHelper::load($publicationSource));

        return $this;
    }

    function __destruct() {
        $this->parser->clear();
    }

    /*
     * Returns html string, depending of decoding attribute
     * */
    public function getHtmlString() {
        return BracketsHelper::unload($this->parser->save());
    }

}
?>