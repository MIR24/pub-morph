<?php
namespace \MIR24\PubMorph;

abstract class AbstractPubMorph 
{
    private $pubSource;
    
    function __construct($publicationSource){
        $this->pubSource = $publicationSource;
        return $this;
    }
    
    abstract public function removeNode();
    abstract public function fillupNode();
}
