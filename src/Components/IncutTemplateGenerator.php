<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Components\AbstractComponent;

use MIR24\Morph\Config\Config;
use MIR24\Morph\Config\Constants;
use MIR24\Morph\Exception\Exception;

class IncutTemplateGenerator extends AbstractComponent {

    /*
     * Implementing interface Process
     * */
    public function process () {
        $this->processRootNodeIdAttribute();
        foreach ($this->processData['fields'] as $data) {
            foreach ($this->findByAttributeName($data['data_attr']['value']) as $node) {
                $this->processAttributeByType($node, $data['data_attr']['value'], $data['value']);
            }
        }
        $this->processReplacePattern();
    }

    /*
     * Insert Incut id
     * */
    private function processRootNodeIdAttribute () {
        $rootNode = $this->parser->firstChild();

        if ($rootNode) {
            $rootNode->setAttribute(Config::get('incut.attr'), $this->processData['id']);
        } else {
            Exception::throw(Constants::EXCEPTION_MSG_ROOT_NOT_NOT_FOUND);
        }

    }

    /*
     * Process data attributes and insert the values
     * */
    private function processAttributeByType ($node, $attrName, $value) {
        $type = $node->{$attrName};
        if (!$type) {
            Exception::throw(Constants::EXCEPTION_MSG_INCUT_ATTR_NAME_NOT_FOUND.$attrName);
        }

        if ($value) {
            switch ($type) {
                case 'content':
                    $node->innertext = $value;
                    break;
                case 'uniqueId':
                    $node->setAttribute($type, $this->getUniqueId());
                    break;
                default:
                    $node->setAttribute($type, $value);
            }
        }
        $node->removeAttribute($attrName);
    }

    /*
     * Process data attributes replace pattern and insert the values
     * */
    private function processReplacePattern () {
        $dataAttrName = Config::get('incutTemplate.dataAttrName');
        $dataAttrPatternName = Config::get('incutTemplate.dataAttrPatternName');
        $regexPattern = Config::get('incutTemplate.regex_extract_pattern');

        foreach ($this->findByAttributeName($dataAttrName) as $node) {
            if ($node->hasAttribute($dataAttrPatternName)) {
                $replacementValue = preg_replace_callback($regexPattern, function ($match) {
                    if (array_key_exists(2, $match)) {
                        switch ($match[2]) {
                            case 'uniqueId':
                                return $this->getUniqueId();
                        }
                    }
                }, $node->getAttribute($dataAttrPatternName));

                $node->setAttribute($node->getAttribute($dataAttrName), $replacementValue);
            }
            $node->removeAttribute($dataAttrName);
            $node->removeAttribute($dataAttrPatternName);
        }
    }

    /*
     * Create unique identifier.
     * */
    private function getUniqueId () {
        return Config::get('incut.uniquePrefix').$this->processData['id'];
    }

    /*
     * Find all nodes by attribute name
     * */
    private function findByAttributeName ($name) {
        return $this->parser->find('*[' . $name . ']');
    }
}
?>