<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Interfaces\Components\Process;

abstract class AbstractComponent implements Process {

    private $parser = NULL;
    private $processType = NULL;
    private $processData = NULL;

    function __construct(&$parser) {
        $this->parser = $parser;

        return $this;
    }

    abstract public function process ();

    public function setProcessType ($type) {
        $this->processType = $type;
    }

    public function setProcessData ($data) {
        $this->processData = $data;
    }

}
?>