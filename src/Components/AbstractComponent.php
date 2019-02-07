<?php
namespace MIR24\Morph\Components;

use MIR24\Morph\Interfaces\Components\Process;

abstract class AbstractComponent implements Process {

    protected $parser = NULL;
    protected $processType = NULL;
    protected $processData = NULL;

    function __construct(&$parser) {
        $this->parser = $parser;

        return $this;
    }

    public function setProcessType ($type) {
        $this->processType = $type;
    }

    public function setProcessData ($data) {
        $this->processData = $data;
    }

}
?>