<?php

namespace MIR24\Morph\Components;

use MIR24\Morph\Config\Config;
use MIR24\Morph\Interfaces\Components\Attribute;
use MIR24\Morph\Traits\DomHelper;
use MIR24\Morph\Traits\IncutReplaceHelper;

class PhotoIncut extends AbstractComponent implements Attribute
{
    use DomHelper, IncutReplaceHelper;

    /*
     * Implementing interface Attribute
     * */
    public function getAttributeValues($type = null)
    {
        return $this->getNodesAttributeValues($this->find(), Config::get('photoIncut.attr'));
    }

    /*
     * Implementing interface Process
     * */
    public function process()
    {
        switch ($this->processType) {
            case 'amp':
                $this->processAmp();
                break;
        }
    }

    /*
     * Processing amp type
     * */
    private function processAmp()
    {
        foreach ($this->processData as $data) {
            if ($data['active'] && $data['code']) {
                $this->replace($data['id'], $data['code']);
            } else {
                $this->remove($data['id']);
            }
        }
    }

    /*
     * Search for DOM node by attribute tag and attribute value.
     * */
    private function findById(int $id)
    {
        return $this->parser->find(sprintf("%s.%s[%s=%d]", Config::get('photoIncut.tag'), Config::get('photoIncut.class'), Config::get('photoIncut.attr'), $id));
    }

    /*
     * Search for DOM nodes by attribute tag.
     * */
    private function find()
    {
        # TODO С классом тут почему-то не находит, а в findById(int $id) находит. Вроде класс распознаётся как значение. Обновить php-simple-html-dom-parser?
//         return $this->parser->find(sprintf("%s.%s[%s]", Config::get('photoIncut.tag'), Config::get('photoIncut.class'), Config::get('photoIncut.attr')));
         return $this->parser->find(sprintf("%s[%s]", Config::get('photoIncut.tag'), Config::get('photoIncut.attr')));
    }
}
