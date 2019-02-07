<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Interfaces\Components\IsAllowed;

use MIR24\Morph\Components\AbstractComponent;
use MIR24\Morph\Config\Config;

class Banner extends AbstractComponent implements IsAllowed {

    public function isAllowed () {
        if (strlen($this->parser->plaintext) > Config::get('ingrid.strlen-pass')) {
            return true;
        }
        return false;
    }

    public function process () {
        $this->insertInText();
    }

    /*
     * Insert banner spot in text after character count
     * */
    private function insertInText () {
        switch ($this->processType) {
            case 0:
                $countLimit = config('ingrid.after-chars-article');
                break;
            default:
                $countLimit = config('ingrid.after-chars-news');
        }

        $countChars = 0;
        foreach ($this->parser->find('p') as $key => $p) {
            $countChars += strlen(strip_tags($p->plaintext));
            if ($countChars >= $countLimit && $key >= Config::get('ingrid.after-p-num')-1) {
                $p->outertext .= $this->processData;
                break;
            }
        }
    }

}
?>