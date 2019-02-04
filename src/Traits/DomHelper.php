<?php
namespace MIR24\Morph\Traits;

trait DomHelper {

    /*
     * Remove parent node if parent node is empty
     * */
    protected function removeParentNodeIfEmpty($node) {
        if (!$node->parent->innertext) {
            $node->parent->outertext = '';
        }
    }

}
?>