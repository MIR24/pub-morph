<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractPubMorph;

class Morph extends AbstractPubMorph
{

    /*
     * Returns html string, dependig of decoding attribute
     * */ 
    public function getHtmlString() {
        if ($this->decode) {
            return $this->parser->save();
        } else {
            return preg_replace_callback(
                config('morph-lib.regex.removeBrackets'),
                function ($matches) {
                    return '{' . htmlspecialchars($matches[config('morph-lib.regex.preg_match_number')]) . '}';
                },
                $this->parser->save()
            );
        }
    }

    /*
     * Returns html string, with encoding, using regex
     * */ 
    public function getHtmlStringWithRegexEncode() {
        
    }

    /*
     * Removes node from publication, than returns publication text morphed.
     * */
    public function removeIncut(int $incutId) {
        foreach ($this->findIncutsById($incutId) as $node) {
            $node->outertext = '';
        }
        return $this;
    }

    /*
     * Fillup node with a specific content, than returns publication text morphed.
     * */ 
     public function replaceIncut(int $incutId, string $content) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->outertext = $content;
         }
         return $this;
     }

     /*
      * Fillup node with a specific content, making incut interactive, than 
      * returns publication text morphed.
      * */ 
     public function makeIncutInactive(int $incutId, string $incutTitle) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->{config('morph-lib.incut.inactive.attr')} = config('morph-lib.incut.inactive.attrContent');
             $node->innertext = config('morph-lib.incut.inactive.msg').$incutTitle;
         }
         return $this;
     }

     /*
      * Getting incut html tags ids attribute
      * */ 
     public function getIncutIds() {
         $result = [];
         foreach ($this->findIncuts() as $node) {
             $result[] = $node->{config('morph-lib.incut.attr')};
         }
         return $result;
     }

     /*
      * Search for DOM node inside pub text by attribute tag, attribute
      * and attribute value
      * */ 
     private function findIncutsById(int $incutId) {
         return $this->parser->find(config('morph-lib.incut.tag').'['. config('morph-lib.incut.attr').'='.$incutId.']');
     }

     /*
      * Search for DOM node inside pub text by attribute tag and attribute value
      * */ 
     private function findIncuts() {
         return $this->parser->find(config('morph-lib.incut.tag').'['. config('morph-lib.incut.attr').']');
     }
}
