<?php
namespace MIR24\Morph\Traits;

use MIR24\Morph\Config\Config;

trait IncutReplaceHelper {

    /*
     * Removes node from publication, than returns publication text morphed.
     * */
    private function remove(int $id)
    {
        foreach ($this->findById($id) as $node) {
            $node->outertext = '';
            $this->removeOldStyles($node);
            $this->removeParentNodeIfEmpty($node);
        }
        return $this;
    }

    /*
     * Fillup node with a specific content, than returns publication text morphed.
     * */
    private function replace(int $id, string $content)
    {
        foreach ($this->findById($id) as $node) {
            $this->removeOldStyles($node);
            $node->outertext = $content;
        }
        return $this;
    }

    /*
     * Remove preset style tag
     * */
    private function removeOldStyles($node)
    {
        $sibling = $node->prev_sibling();

        if ($sibling && $sibling->tag === 'style') {
            $sibling->outertext = '';
        }
    }

}
