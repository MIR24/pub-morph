<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Components\AbstractComponent;

use MIR24\Morph\Config\Config;

class Amp extends AbstractComponent {

    /*
     * Implementing interface Process
     * */
    public function process () {
        $this->headerIncludes = null;

        foreach (Config::get('amp.blocks') as $one) {
            foreach ($this->parser->find($one['type']) as $element) {
                $config_match = null;
                if (array_key_exists('regex_match_href', $one)) {
                    foreach ($element->find('a') as $a_teg) {
                        if (preg_match($one['regex_match_href'], $a_teg->href, $config_match)) {
                            break;
                        }
                    }
                }
                if (array_key_exists('regex_match_src', $one)) {
                    preg_match($one['regex_match_src'], $element->src, $config_match);
                }
                if (array_key_exists('div_attribute', $one)) {
                    $config_match = $element->getAttribute($one['div_attribute']);
                }
                if ($config_match) {
                    if (strlen($one['header_include']) > 0 && !strpos($this->headerIncludes, $one['header_include_match'])) {
                        $this->headerIncludes .= $one['header_include'];
                    }
                    if (is_array($config_match)) {
                        $element->parent->innertext = preg_replace(Config::get('amp.regex_match_brackets'), $config_match[1], $one['exit_tag']);
                    } else {
                        $element->parent->innertext = preg_replace(Config::get('amp.regex_match_brackets'), $config_match, $one['exit_tag']);
                    }
                }
            }
        }

        foreach (Config::get('amp.remove_by_tag_name') as $tagName) {
            foreach ($this->parser->find($tagName) as $elementToRemove){
                $elementToRemove->outertext = '';
                if (!$elementToRemove->parent->innertext) {
                    $elementToRemove->parent->outertext = '';
                }
            }
        }

        foreach (Config::get('amp.remove_tag_by_name') as $tagName) {
            foreach ($this->parser->find($tagName) as $elementToRemove) {
                $elementToRemove->parent->innertext = $elementToRemove->innertext;
            }
        }

        foreach (Config::get('amp.remove_attr_by_name') as $attrName) {
            foreach ($this->parser->find('*['. $attrName .']') as $elementToRemove) {
                $elementToRemove->$attrName = NULL;
            }
        }

    }

    /*
     * Implementing interface Process
     * */
    public function getExtraProcessData () {
        return [
            'extraHeaders' => $this->headerIncludes,
        ];
    }

}
?>