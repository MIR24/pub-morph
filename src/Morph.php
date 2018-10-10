<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractPubMorph;

class Morph extends AbstractPubMorph
{
    public function getHtmlString() {
        return $this->parser->save();
    }
    public function getHtmlStringWithRegexEncode(string $regex, int $regexMatchNumber) {
        return preg_replace_callback(
            $regex,
            function ($matches) use ($regexMatchNumber) {
                return '{' . htmlspecialchars($matches[$regexMatchNumber]) . '}';
            },
            $this->parser->save()
        );
    }
    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * removes node from publication, than returns publication text morphed.
     * */
    public function removeIncut(int $id) {
        return config('constants.incut');
    }
    public function nodeCheckAttrAndReplace(string $nodeAttrName, $checkActive) {
        foreach ($this->parser->find('*['. $nodeAttrName .']') as $element) {
            $newValue = $checkActive($element->$nodeAttrName);
            if (!$newValue) {
                $element->parent->innertext = '';
            } else {
                $element->innertext = $newValue;
            }
        }
        return $this;
    }

    /*
     * Search for DOM node inside pub text by attribute name and attribute value,
     * fillup node with a specific content, than returns publication text morphed.
     * */ 
    public function setNodesContentByAttrNameValue(string $nodeAttrName, array $nodeAttrValueContent) {
        foreach ($this->parser->find('*['. $nodeAttrName .']') as $element) {
            if (isset($nodeAttrValueContent[$element->$nodeAttrName])) {
                $element->innertext = $nodeAttrValueContent[$element->$nodeAttrName];
            }
        }
        return $this;
    }
}
