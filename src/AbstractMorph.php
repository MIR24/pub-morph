<?php
namespace MIR24\Morph;

use Sunra\PhpSimple\HtmlDomParser;

use MIR24\Morph\Helpers\BracketsHelper;

use MIR24\Morph\Components\Incut;
use MIR24\Morph\Components\Banner;
use MIR24\Morph\Components\IncutTemplateGenerator;
use MIR24\Morph\Components\Amp;
use MIR24\Morph\Components\Image;

abstract class AbstractMorph
{
    protected $parser;
    protected $component;

    function __construct ($publicationSource) {
        $this->loadParser($publicationSource);

        return $this;
    }

    function __destruct () {
        $this->parser->clear();
    }

    private function loadParser ($str) {
        $this->parser = HtmlDomParser::str_get_html(BracketsHelper::load($str));
    }

    protected function loadComponent ($className) {
        $this->updateParser();
        $this->component = new $className ($this->parser);
        $this->parser->clear();
    }

    private function updateParser () {
        if ($this->component) {
            $this->loadParser($this->component->getHtmlString());
        }
    }

    /*
     * Returns html string, depending of decoding attribute
     * */
    public function getHtmlString () {
        $this->updateParser();

        return BracketsHelper::unload($this->parser->save());
    }

}
?>