<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractMorph;
use MIR24\Morph\Config\Config;

use MIR24\Morph\Components\Incut;
use MIR24\Morph\Components\Banner;

class Morph extends AbstractMorph {

    public function incut () {
        $this->component = new Incut($this->parser);

        return $this;
    }

    public function banner () {
        $this->component = new Banner($this->parser);

        return $this;
    }

    public function process () {
        $this->component->process();

        return $this;
    }

    public function setProcessType ($type) {
        $this->component->setProcessType($type);

        return $this;
    }

    public function setProcessData ($data) {
        $this->component->setProcessData($data);

        return $this;
    }

    public function getAttributes ($type = NULL) {
        return $this->component->getAttribute($type);
    }

    public function isAllowed () {
        return $this->component->isAllowed();
    }

}
?>