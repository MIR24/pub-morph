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

    /*
     * Implementing interface Process
     * */
    public function setProcessType ($type) {
        $this->processType = $type;
    }

    /*
     * Implementing interface Process
     * */
    public function setProcessData ($data) {
        $this->processData = $data;
    }

    /*
     * Implementing interface Process
     * */
    public function getExtraProcessData () {
        return null;
    }

}
?>