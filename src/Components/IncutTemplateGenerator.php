<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Components\AbstractComponent;

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
                $this->processAttributeByType($node, $node->{$data['data_attr']['value']}, $data['value']['value']);
            }
        }
    }

    private function processRootNodeIdAttribute () {
        $rootNode = $this->parser->first_child();

        if ($rootNode) {
            $idAttr = $rootNode->{Config::get('incut.attr')};
            if (!$idAttr || $idAttr === '') {
                $idAttr = $this->processData['id'];
            }
            
        } else {
            Exception::throw(Constants::EXCEPTION_MSG_ROOT_NOT_NOT_FOUND);
        }

    }

    private function processAttributeByType ($node, $type, $value) {
        if (!$type) {
            Exception::throw(Constants::EXCEPTION_MSG_INCUT_ATTR_NAME_NOT_FOUND);
        }

        if (!$value) {
            $node->{$type} = null;
            return true;
        }

        switch ($type) {
            case 'content':
                $node->innertext = $value;
                break;
            default:
                $node->{$type} = $value;
        }
    }

    private function findByAttributeName ($name) {
        return $this->parser->find('*[' . $name . ']');
    }
}
?>