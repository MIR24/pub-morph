<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Components\AbstractComponent;
use MIR24\Morph\Helpers\LightboxHelper;

use MIR24\Morph\Config\Config;
use MIR24\Morph\Config\Constants;
use MIR24\Morph\Exception\Exception;

class IncutTemplateGenerator extends AbstractComponent {

    /*
     * Implementing interface Process
     * */
    public function process () {
        switch ($this->processType) {
            case 'backend':
                $this->processBackend();
                break;
            case 'fontend':
            default:
                $this->processFrontend();
        }
    }

    /*
     * Processing backend type
     * */
    private function processFrontend () {
        $this->processTemplateBaseLogic();
        $this->processAdditionalAttributeByType();
    }

    /*
     * Processing frontend type
     * */
    private function processBackend () {
        $this->processTemplateBaseLogic();
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
            Exception::throw(Constants::EXCEPTION_MSG_INCUT_ATTR_NAME_NOT_FOUND);
        }

        if ($value) {
            switch ($type) {
                case 'content':
                    $node->innertext = $value;
                    break;
                default:
                    $node->setAttribute($type, $value);
            }
        }
        $node->removeAttribute($attrName);
    }

    /*
     * Base processing logic
     * */
    private function processTemplateBaseLogic () {
        $this->processRootNodeIdAttribute();
        foreach ($this->processData['fields'] as $data) {
            foreach ($this->findByAttributeName($data['data_attr']['value']) as $node) {
                $this->processAttributeByType($node, $data['data_attr']['value'], $data['value']);
            }
        }
    }

    /*
     * Process additional data attributes and insert the values
     * */
    private function processAdditionalAttributeByType () {
        foreach (Config::get('incutTemplate.additionalAttrTypes') as $attrTypeKey => $attrTypeValue) {
            foreach ($this->findByAttributeName($attrTypeKey) as $node) {
                $tempValue = null;

                foreach ($this->processData['fields'] as $data) {
                    if ($data['data_attr']['value'] === $attrTypeValue) {
                        $tempValue = $data['value'];
                        break;
                    }
                }

                if ($tempValue) {
                    switch ($attrTypeKey) {
                        case 'data-attribute-mir24-lightbox-root':
                            $node->outertext = LightboxHelper::process($tempValue, $tempValue, '', $node->outertext);
                            break;
                    }
                }

            }

            $node->removeAttribute($attrTypeKey);
        }
    }

    /*
     * Find all nodes by attribute name
     * */
    private function findByAttributeName ($name) {
        return $this->parser->find('*[' . $name . ']');
    }
}
?>