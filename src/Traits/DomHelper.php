<?php
namespace MIR24\Morph\Traits;

use MIR24\Morph\Config\Config;

trait DomHelper {

    /*
     * Remove parent node if parent node is empty
     * */
    private function removeParentNodeIfEmpty ($node) {
        if (!$node->parent->innertext || $node->parent->innertext === Config::get('emptyBrackets')) {
            $node->parent->outertext = '';
        }
    }

    /*
     * Returns nodes attribute values
     * */
    private function getNodesAttributeValues ($nodes, $attr) {
        $result = [];
        foreach ($nodes as $node) {
            $result[] = $node->{$attr};
        }
        return $result;
    }

}
?>