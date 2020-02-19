<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractMorph;

class Morph extends AbstractMorph {

    public function incut () {
        $this->loadComponent('Incut');

        return $this;
    }

    public function banner () {
        $this->loadComponent('Banner');

        return $this;
    }

    public function incutTemplateGenerator () {
        $this->loadComponent('IncutTemplateGenerator');

        return $this;
    }

    public function amp () {
        $this->loadComponent('Amp');

        return $this;
    }

    public function image () {
        $this->loadComponent('Image');

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

    public function getExtraProcessData () {
        return $this->component->getExtraProcessData();
    }

    public function getAttributeValues ($type = NULL) {
        return $this->component->getAttributeValues($type);
    }

    public function isAllowed () {
        return $this->component->isAllowed();
    }

}
?>