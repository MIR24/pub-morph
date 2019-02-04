<?php
namespace MIR24\Morph;

use MIR24\Morph\Interfaces\Components\Process;
use MIR24\Morph\Interfaces\Components\Attribute;

use MIR24\Morph\Components\AbstractComponent;
use MIR24\Morph\Config\Config;

use MIR24\Morph\Traits\DomHelper;

class Incut extends AbstractComponent implements Process, Attribute {

    use DomHelper;

    public function getAttribute ($type) {
        $result = [];
        foreach ($this->findIncuts() as $node) {
            $result[] = $node->{Config::get('incut.attr')};
        }
        return $result;
    }

    public function process () {
        switch ($this->processType) {
            case 'back-end':
                foreach ($this->processData as $one) {
                    if ($one['active']) {
                        $this->replaceIncut($one['id'], $one['code']);
                    } else {
                        $this->removeIncut($one['id']);
                    }
                }
                break;
            case 'font-end':
            default:
                foreach ($this->processData as $one) {
                    if ($one['active']) {
                        $this->replaceIncut($one['id'], $one['code']);
                    } else {
                        $this->removeIncut($one['id']);
                    }
                }
        }
    }

    /*
     * Removes node from publication, than returns publication text morphed.
     * */
    protected function removeIncut(int $incutId) {
        foreach ($this->findIncutsById($incutId) as $node) {
            $node->outertext = '';
            $this->removeParentNodeIfEmpty($node);
        }
        return $this;
    }

    /*
     * Fillup node with a specific content, than returns publication text morphed.
     * */
    protected function replaceIncut(int $incutId, string $content) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->outertext = $content;
         }
         return $this;
     }

     /*
      * Fillup node with a specific content, making incut interactive, than
      * returns publication text morphed.
      * */
      protected function makeIncutInactive (int $incutId, string $incutTitle) {
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
      protected function makeIncutDeleted (int $incutId) {
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
     protected function findIncutsById (int $incutId) {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').'='.$incutId.']');
     }

     /*
      * Search for DOM node inside pub text by attribute tag and attribute value
      * */
     protected function findIncuts () {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').']');
     }

}
?>