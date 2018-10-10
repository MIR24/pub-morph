<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractPubMorph;

class Morph extends AbstractPubMorph
{
    public function getHtmlString() {
        return $this->parser->save();
    }
    public function getHtmlStringWithRegexEncode() {
        return preg_replace_callback(
            config('morph-lib.regex.removeBrackets'),
            function ($matches) {
                return '{' . htmlspecialchars($matches[config('morph-lib.regex.preg_match_number')]) . '}';
            },
            $this->parser->save()
        );
    }
    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * removes node from publication, than returns publication text morphed.
     * */
    public function removeIncut(int $incutId) {
        foreach ($this->findIncutsById($incutId) as $node) {
            $node->parent->outertext = '';
        }
        return $this;
    }

    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * fillup node with a specific content, than returns publication text morphed.
     * */ 
     public function replaceIncut(int $incutId, string $content) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->outertext = $content;
         }
         return $this;
     }
     
     public function makeIncutInactive(int $incutId, string $incutTitle) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->{config('morph-lib.incut.inactive.attr')} = config('morph-lib.incut.inactive.attrContent');
             $node->innertext = config('morph-lib.incut.inactive.msg').$incutTitle;
         }
         return $this;
     }
     
     public function getIncutIds() {
         $result = [];
         foreach ($this->findIncuts() as $node) {
             $result[] = $node->{config('morph-lib.incut.attr')};
         }
         return $result;
     }
     
     private function findIncutsById(int $incutId) {
         return $this->parser->find(config('morph-lib.incut.tag').'['. config('morph-lib.incut.attr').'='.$incutId.']');
     }
     
     private function findIncuts() {
         return $this->parser->find(config('morph-lib.incut.tag').'['. config('morph-lib.incut.attr').']');
     }
}
