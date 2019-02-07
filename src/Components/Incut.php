<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Interfaces\Components\Attribute;

use MIR24\Morph\Components\AbstractComponent;
use MIR24\Morph\Config\Config;

use MIR24\Morph\Traits\DomHelper;

class Incut extends AbstractComponent implements Attribute {

    use DomHelper;

    /*
     * Implementing interface Attribute
     * */
    public function getAttributeValues ($type = NULL) {
        return $this->getNodesAttributeValue($this->find(), Config::get('incut.attr'));
    }

    /*
     * Implementing interface Process
     * */
    public function process () {
        $this->allFoundIds = $this->getAttributeValues('id');

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
        foreach ($this->processData as $data) {
            if ($data['active']) {
                $this->replace($data['id'], $data['code']);
            } else {
                $this->remove($data['id']);
            }
            $this->allFoundIds = array_diff($this->allFoundIds, [$data['id']]);
        }

        if (!empty($this->allFoundIds)) {
            foreach ($this->allFoundIds as $id) {
                $this->remove($id);
            }
            unset($this->allFoundIds);
        }
    }

    /*
     * Processing frontend type
     * */
    private function processBackend () {
        foreach ($this->processData as $data) {
            if ($data['active']) {
                $this->replace($data['id'], $data['code']);
            } else {
                $this->makeInactive($data['id'], $data['head_text']);
            }
            $this->allFoundIds = array_diff($this->allFoundIds, [$data['id']]);
        }

        if (!empty($this->allFoundIds)) {
            foreach ($this->allFoundIds as $id) {
                $this->makeDeleted($id);
            }
            unset($this->allFoundIds);
        }
    }

    /*
     * Removes node from publication, than returns publication text morphed.
     * */
    private function remove(int $id) {
        foreach ($this->findById($id) as $node) {
            $node->outertext = '';
            $this->removeParentNodeIfEmpty($node);
        }
        return $this;
    }

    /*
     * Fillup node with a specific content, than returns publication text morphed.
     * */
    private function replace(int $id, string $content) {
         foreach ($this->findById($id) as $node) {
             $node->outertext = $content;
         }
         return $this;
     }

     /*
      * Fillup node with a specific content, making incut interactive, than
      * */
    private function makeInactive (int $id, string $title) {
         foreach ($this->findById($id) as $node) {
             $node->{Config::get('incut.inactive.attr')} = Config::get('incut.inactive.attrContent');
             $node->innertext = Config::get('incut.inactive.msg').$title;
         }
         return $this;
     }

     /*
      * Fillup node with a specific content, making incut deleted, than
      * */
    private function makeDeleted (int $id) {
         foreach ($this->findById($id) as $node) {
             $node->{Config::get('incut.inactive.attr')} = Config::get('incut.inactive.attrContent');
             $node->innertext = Config::get('incut.delete.msg');
         }
         return $this;
     }

     /*
      * Search for DOM node by attribute tag, attribute and attribute value
      * */
    private function findById (int $id) {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').'='.$id.']');
     }

     /*
      * Search for DOM node by attribute tag and attribute value
      * */
    private function find () {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').']');
     }

}
?>