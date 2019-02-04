<?php
namespace MIR24\Morph\Interfaces\Components;

interface Process {
    public function process ();
    public function setProcessType ($type);
    public function setProcessData ($data);
}
?>