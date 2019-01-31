<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractPubMorph;
use MIR24\Morph\Config\Config;

class Morph extends AbstractPubMorph
{
    #------------------------ DOM Helpers start --------------------------------
    /*
     * Returns html string, dependig of decoding attribute
     * */
    public function getHtmlString() {
        if ($this->decode) {
            return preg_replace_callback(
                Config::get('regex.removeBrackets'),
                function ($matches) {
                    return '{' . htmlspecialchars($matches[Config::get('regex.preg_match_number')]) . '}';
                },
                $this->parser->save()
            );
        } else {
            return $this->parser->save();
        }
    }

    /*
     * Return parser plaintext
     * */
    public function getPlainText() {
        return $this->parser->plaintext;
    }

    /*
     * Remove parent node if parent node is empty
     * */
    protected function removeParentNodeIfEmpty($node) {
        if (!$node->parent->innertext) {
            $node->parent->outertext = '';
        }
    }
    #------------------------ DOM Helpers end ----------------------------------
    #----------------------- Incut start ---------------------------------------
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
             $node->{Config::get('incut.inactive.attr')} = Config::get('incut.inactive.attrContent');
             $node->innertext = Config::get('incut.inactive.msg').$incutTitle;
         }
         return $this;
     }

     /*
      * Fillup node with a specific content, making incut deleted, than
      * returns publication text morphed.
      * */
     public function makeIncutDeleted(int $incutId) {
         foreach ($this->findIncutsById($incutId) as $node) {
             $node->{Config::get('incut.inactive.attr')} = Config::get('incut.inactive.attrContent');
             $node->innertext = Config::get('incut.delete.msg');
         }
         return $this;
     }

     /*
      * Getting incut html tags ids attribute
      * */
     public function getIncutIds() {
         $result = [];
         foreach ($this->findIncuts() as $node) {
             $result[] = $node->{Config::get('incut.attr')};
         }
         return $result;
     }

     /*
      * Search for DOM node inside pub text by attribute tag, attribute
      * and attribute value
      * */
     protected function findIncutsById(int $incutId) {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').'='.$incutId.']');
     }

     /*
      * Search for DOM node inside pub text by attribute tag and attribute value
      * */
     protected function findIncuts() {
         return $this->parser->find(Config::get('incut.tag').'['. Config::get('incut.attr').']');
     }
    #----------------------- Incut end -----------------------------------------
    #----------------------- Banner places start -------------------------------
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
    #----------------------- Banner places end ---------------------------------
}
?>