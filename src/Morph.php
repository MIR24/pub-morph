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
            return preg_replace_callback(
                config('morph-lib.regex.removeBrackets'),
                function ($matches) {
                    return '{' . htmlspecialchars($matches[config('morph-lib.regex.preg_match_number')]) . '}';
                },
                $this->parser->save()
            );
        } else {
            return $this->parser->save();
        }
    }

    /*
     * Removes node from publication, than returns publication text morphed.
     * */
    public function removeIncut(int $incutId) {
        foreach ($this->findIncutsById($incutId) as $node) {
            $node->outertext = '';
            $this->removeParentNodeIfEmpty($node);
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
      * Return parser plaintext
      * */
     public function getPlainText() {
         return $this->parser->plaintext;
     }

     /*
      * Insert banner spot in text after character count
      * */ 
     public function insertBannerInTextAfter(int $countLimit, int $pNum, string $content = NULL) {
         if ($content) {
             $countChars = 0;
             foreach ($this->parser->find('p') as $key => $p) {
                 $countChars += strlen(strip_tags($p->plaintext));
                 if ($countChars >= $countLimit && $key >= $pNum) {
                     $p->outertext .= $content;
                     break;
                 }
             } 
         }
         return $this;
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

     /*
      * Remove parent node if parent node is empty
      * */
     private function removeParentNodeIfEmpty($node) {
         if (!$node->parent->innertext) {
             $node->parent->outertext = '';
         }
     }
}
