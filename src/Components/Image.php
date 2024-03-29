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

        switch ($this->processType) {
            case 'amp':
                $nodes = $this->findForProcess();
                break;
            default:
                $nodes = $this->find();
        }

        foreach ($nodes as $node) {
            if ($this->isProcessAllowed($node)) {
                $result[] = [
                    'id' => $node->getAttribute(Config::get('image.attrImageIdName')),
                    'src' => $node->getAttribute('src'),
                    'data-lightbox' => $node->getAttribute('data-lightbox')
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
            if ($data['id']) {
                $this->replaceDefault($this->findByAttrTypeAndContent(Config::get('image.attrImageIdName'), $data['id']), $data['lightboxSrc'] ?? null);
            } else if ($data['src']) {
                $this->replaceDefault($this->findByAttrTypeAndContent('src', $data['src']), $data['lightboxSrc'] ?? null);
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
                $style = $node->getAttribute('style');
                $imgStyle = '';

                $style = preg_replace_callback(Config::get('image.regex-img-style-keep'), function ($matches) use (&$imgStyle) {
                    $imgStyle .= $matches[0];
                    return '';
                }, $style);
                $node->setAttribute('style', $imgStyle);
                $content = $lightboxSrc
                    ? LightboxHelper::process($lightboxSrc, $lightboxSrc, $caption, $node->outertext)
                    : $node->outertext;

                $node->outertext = $this->wrapInFigure($content, $caption, $style);
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
            $node->tag = 'figure';
            $node->outertext = $ampImg;
            continue;
        }

        $imageProcessByAttrName = Config::get('image.processByAttribute');
        foreach ($this->processData as $data) {
            foreach ($this->findAmpImageByAttrTypeAndContent('src', $data['src']) as $imageNode) {
                $type = $imageNode->getAttribute($imageProcessByAttrName);
                $imageNode->removeAttribute($imageProcessByAttrName);

                switch ($type) {
                    case 'size':
                        if (array_key_exists('width', $data) && array_key_exists('height', $data)) {
                            $imageNode->setAttribute('width', $data['width']);
                            $imageNode->setAttribute('height', $data['height']);
                        } else {
                            $imageNode->outertext = '';
                        }
                        break;
                }
            }
        }

    }

    /*
     * Search for DOM node by attribute tag.
     * */
    private function find () {
        return $this->parser->find('img');
    }

    /*
     * Search for DOM node marked for change.
     * */
    private function findForProcess () {
        return $this->parser->find('*['.Config::get('image.processByAttribute').']');
    }

    /*
     * Search for DOM node by attribute tag and attribute value.
     * */
    private function findByAttrTypeAndContent ($type, $content) {
        return $this->parser->find('img['.$type.'='.$content.']');
    }

    /*
     * Search for DOM node by attribute tag and attribute value.
     * */
    private function findAmpImageByAttrTypeAndContent ($type, $content) {
        return $this->parser->find('amp-img['.$type.'='.$content.']');
    }

    /*
     * Check if image tag is allowed for change.
     * */
    private function isProcessAllowed ($node) {
        $parent = $node->parent();
        if ($parent && $parent->tag === 'p' || $node->tag === 'amp-img') {
            return true;
        }
        return false;
    }

    /*
     * Warp with figure html tag.
     * */
    private function wrapInFigure ($content, $caption = NULL, $style = NULL) {
        if ($caption) {
            $caption = '<figcaption>'.$caption.'</figcaption>';
        }

        return '<figure '.($style ? 'style="'.$style.'"' : '').' class="'.Config::get('image.figure-class').'">'.$content.$caption.'</figure>';
    }

}
?>
