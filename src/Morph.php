<?php
namespace MIR24\Morph;

use MIR24\Morph\AbstractPubMorph;

class Morph extends AbstractPubMorph
{
    public function getHtmlString() {
        return $this->parser->save();
    }
    public function getHtmlStringWithRegexEncode($regex, $regexMatchNumber) {
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
    public function clearNodesContentByAttrName(string $nodeAttrName) {
        foreach ($this->parser->find('*['. $nodeAttrName .']') as $element) {
            $element->innertext = '';
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

    public function removeRepeatingScripts() {
        $sameScripts = [];
        foreach ($this->parser->find('script') as $element) {
            if (in_array($element->outertext, $sameScripts)) {
                $element->outertext = '';
            } else {
                $sameScripts[] = $element->outertext;
            }
        }
        return $this;
    }

    public function replaceImgSrcWithFullSrcCallback(string $compareTo, $callback) {
        foreach ($this->parser->find('img') as $element) {
            if (strpos($element->src, $compareTo) === 0) {
                $element->src = $callback($element->src);
            }
        }
        return $this;
    }
}
