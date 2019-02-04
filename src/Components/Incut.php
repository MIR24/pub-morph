<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Interfaces\Components\Process;
use MIR24\Morph\Interfaces\Components\Attribute;

use MIR24\Morph\Components\AbstractComponent;
use MIR24\Morph\Config\Config;

use MIR24\Morph\Traits\DomHelper;

class Incut extends AbstractComponent implements Process, Attribute {

    use DomHelper;

    public function getAttributes ($type = NULL) {
        return $this->getNodesAttributeValue($this->findIncuts(), Config::get('incut.attr'));
    }

    public function process () {
        $this->allIds = $this->getAttributes('id');

        switch ($this->processType) {
            case 'backend':
                $this->processBackend();
                break;
            case 'fontend':
            default:
                $this->processFrontend();
        }
    }

    private function processFrontend ($array) {
        foreach ($this->processData as $one) {
            if ($one['active']) {
                $this->replaceIncut($one['id'], $one['code']);
            } else {
                $this->removeIncut($one['id']);
            }
            $this->allIds = array_diff($this->allIds, [$one['id']]);
        }

        if (!empty($this->allIds)) {
            foreach ($this->allIds as $id) {
                $this->removeIncut($id);
            }
            unset($this->allIds);
        }
    }

    private function processBackend () {
        foreach ($this->processData as $one) {
            if ($one['active']) {
                $this->replaceIncut($one['id'], $one['code']);
            } else {
                $this->makeIncutInactive($one['id'], $one['head_text']);
            }
            $this->allIds = array_diff($this->allIds, [$one['id']]);
        }

        if (!empty($this->allIds)) {
            foreach ($this->allIds as $id) {
                $this->makeIncutDeleted($id);
            }
            unset($this->allIds);
        }
    }

    /*
     * Removes node from publication, than returns publication text morphed.
     * */
    private function removeIncut(int $incutId) {
        foreach ($this->findIncutsById($incutId) as $node) {
            $node->outertext = '';
            $this->removeParentNodeIfEmpty($node);
        }
        return $this;
    }

    /*
     * Fillup node with a specific content, than returns publication text morphed.
     * */
    private function replaceIncut(int $incutId, string $content) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->outertext = $content;
         }
         return $this;
     }

     /*
      * Fillup node with a specific content, making incut interactive, than
      * returns publication text morphed.
      * */
    private function makeIncutInactive (int $incutId, string $incutTitle) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->{Config::get('incut.inactive.attr')} = Config::get('incut.inactive.attrContent');
             $node->innertext = Config::get('incut.inactive.msg').$incutTitle;
         }
         return $this;
     }

     /*
      * Fillup node with a specific content, making incut deleted, than
      * returns publication text morphed.
      * */
    private function makeIncutDeleted (int $incutId) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->{Config::get('incut.inactive.attr')} = Config::get('incut.inactive.attrContent');
             $node->innertext = Config::get('incut.delete.msg');
         }
         return $this;
     }

     /*
      * Search for DOM node inside pub text by attribute tag, attribute
      * and attribute value
      * */
    private function findIncutsById (int $incutId) {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').'='.$incutId.']');
     }

     /*
      * Search for DOM node inside pub text by attribute tag and attribute value
      * */
    private function findIncuts () {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').']');
     }

}
?>