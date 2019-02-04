<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Interfaces\Components\Process;
use MIR24\Morph\Interfaces\Components\IsAllowed;

use MIR24\Morph\Components\AbstractComponent;
use MIR24\Morph\Config\Config;

class Banner extends AbstractComponent implements Process, IsAllowed {

    public function isAllowed () {
        return true;
    }

    public function process () {
        $this->insertInText();
    }

    /*
     * Insert banner spot in text after character count
     * */
    /*public function insertBannerInTextAfter(int $countLimit, int $pNum, string $content = NULL) {
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
    }*/

    private function insertInText () {}

}
?>