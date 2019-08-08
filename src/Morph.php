<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractMorph;

use MIR24\Morph\Components\Incut;
use MIR24\Morph\Components\Banner;
use MIR24\Morph\Components\IncutTemplateGenerator;

class Morph extends AbstractMorph {

    public function incut () {
        $this->component = new Incut($this->parser);

        return $this;
    }

    public function banner () {
        $this->component = new Banner($this->parser);

        return $this;
    }

    public function incutTemplateGenerator () {
        $this->component = new IncutTemplateGenerator($this->parser);

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

    public function getAttributeValues ($type = NULL) {
        return $this->component->getAttributeValues($type);
    }

    public function isAllowed () {
        return $this->component->isAllowed();
    }

}
?>