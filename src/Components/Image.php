<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Interfaces\Components\Attribute;

use MIR24\Morph\Components\AbstractComponent;
use MIR24\Morph\Helpers\LightboxHelper;

use MIR24\Morph\Config\Config;

use MIR24\Morph\Traits\DomHelper;

class Image extends AbstractComponent implements Attribute {

    use DomHelper;

    /*
     * Implementing interface Attribute
     * */
    public function getAttributeValues ($type = NULL) {
        $result = [];

        foreach ($this->find() as $node) {
            if ($this->isProcessAllowed($node)) {
                $result[] = [
                    'id' => $node->getAttribute(Config::get('image.attrImageIdName')),
                    'src' => $node->getAttribute('src')
                ];
            }
        }

        return $result;
    }

    /*
     * Implementing interface Process
     * */
    public function process () {
        switch ($this->processType) {
            case 'amp': 
                $this->processAmp();
                break;
            default:
                $this->processDefault();
        }
    }

    /*
     * Processing default type
     * */
    private function processDefault () {
        foreach ($this->processData as $data) {
            if ($data['lightboxSrc']) {
                if ($data['id']) {
                    $this->replaceDefault($this->findByAttrTypeAndContent(Config::get('image.attrImageIdName'), $data['id']), $data['lightboxSrc']);
                } else if ($data['src']) {
                    $this->replaceDefault($this->findByAttrTypeAndContent('src', $data['src']), $data['lightboxSrc']);
                }
            }
        }
    }

    /*
     * Replacing image html content
     * */
    private function replaceDefault ($nodes, $lightboxSrc) {
        foreach($nodes as $node) {
            if ($this->isProcessAllowed($node)) {
                $caption = $node->getAttribute(Config::get('image.attrImageCaptionName'));
                $node->outertext = $this->wrapInFigure(LightboxHelper::process($lightboxSrc, $lightboxSrc, $caption, $node->outertext), $caption);
            }
        }
    }

    /*
     * Processing amp type
     * */
    private function processAmp () {
        foreach($this->find() as $node) {
            $config_match = null;
            $ampImg = Config::get('image.amp.exit_tag');

            $imgHeight = $node->height;
            if (!$imgHeight) {
                preg_match(Config::get('image.amp.style.height'), $node->getAttribute('style'), $config_match);
                if ($config_match && $config_match[1] !== 'auto') {
                    $imgHeight = $config_match[1];
                }
            }

            $imgWidth = $node->width;
            if (!$imgWidth) {
                preg_match(Config::get('image.amp.style.width'), $node->getAttribute('style'), $config_match);
                if ($config_match && $config_match[1] !== 'auto') {
                    $imgWidth = $config_match[1];
                }
            }

            if (!$imgWidth || !$imgHeight) {
                $ampImg = str_replace(Config::get('image.amp.replace.height'), Config::get('image.amp.default.height'), $ampImg);
                $ampImg = str_replace(Config::get('image.amp.replace.width'), Config::get('image.amp.default.width'), $ampImg);
            } else {
                $ampImg = str_replace(Config::get('image.amp.replace.height'), $imgHeight, $ampImg);
                $ampImg = str_replace(Config::get('image.amp.replace.width'), $imgWidth, $ampImg);
            }

            $imgSrc = $node->src;
            if (!$imgSrc) {
                $node->parent->innertext = '';
                continue;
            }

            $httpsSwitch = strpos($imgSrc, 'http:');
            if ($httpsSwitch) {
                $imgSrc= 'https:' . substr($imgSrc, $httpsSwitch, -1);
            }

            $ampImg = $this->wrapInFigure(str_replace(Config::get('image.amp.replace.src'), $imgSrc, $ampImg), $node->getAttribute(Config::get('image.attrImageCaptionName')));
            $node->parent->innertext = $ampImg;
            continue;
        }
    }

    /*
     * Search for DOM node by attribute tag.
     * */
    private function find () {
        return $this->parser->find('img');
    }

    /*
     * Search for DOM node by attribute tag and attribute value.
     * */
    private function findByAttrTypeAndContent ($type, $content) {
        return $this->parser->find('img['.$type.'='.$content.']');
    }

    /*
     * Check if image tag is allowed for change.
     * */
    private function isProcessAllowed ($node) {
        $parent = $node->parent();
        if ($parent && $parent->tag === 'p') {
            return true;
        }
        return false;
    }

    /*
     * Warp with figure html tag.
     * */
    private function wrapInFigure ($content, $caption = NULL) {
        if ($caption) {
            $caption = '<figcaption>'.$caption.'</figcaption>';
        }

        return '<figure class="'.Config::get('image.figure-class').'">'.$content.$caption.'</figure>';
    }

}
?>