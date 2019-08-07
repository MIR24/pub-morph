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
                $this->processAttributeByType($node, $node->{$data['data_attr']['value']}, $data['value']);
            }
        }
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
    private function processAttributeByType ($node, $type, $value) {
        if (!$type) {
            Exception::throw(Constants::EXCEPTION_MSG_INCUT_ATTR_NAME_NOT_FOUND);
        }

        if ($value) {
            switch ($type) {
                case 'content':
                    $node->innertext = $value;
                    break;
                default:
                    $node->{$type} = $value;
            }
        }
        $node->removeAttribute($type);
    }

    /*
     * Find all nodes by attribute name
     * */
    private function findByAttributeName ($name) {
        return $this->parser->find('*[' . $name . ']');
    }
}
?>