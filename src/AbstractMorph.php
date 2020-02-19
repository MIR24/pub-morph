<?php
namespace MIR24\Morph;

use MIR24\Morph\Interfaces\DomParser\GetDomData;

use Sunra\PhpSimple\HtmlDomParser;

use MIR24\Morph\Config\Constants;

use MIR24\Morph\Helpers\BracketsHelper;

use MIR24\Morph\Components\Incut;
use MIR24\Morph\Components\Banner;
use MIR24\Morph\Components\IncutTemplateGenerator;
use MIR24\Morph\Components\Amp;
use MIR24\Morph\Components\Image;

abstract class AbstractMorph implements GetDomData
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

    /*
     * Setting parser for use
     * */
    private function loadParser ($str) {
        $this->parser = HtmlDomParser::str_get_html(BracketsHelper::load($str));
    }

    /*
     * Helper function, loading component for use
     * */
    protected function loadComponent ($className) {
        $this->updateParser();
        $className = Constants::COMPONENTS_LOCATION . $className;
        $this->component = new $className ($this->parser);
    }

    /*
     * Updating parser data, after component processing
     * */
    private function updateParser () {
        if ($this->component) {
            $this->loadParser($this->component->getHtmlString());
        }
    }

    /*
     * Implementing interface GetDomData
     * */
    public function getHtmlString () {
        $this->updateParser();

        return BracketsHelper::unload($this->parser->save());
    }

}
?>