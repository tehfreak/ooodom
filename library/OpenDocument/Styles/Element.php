<?php

/**
 * OpenDocument Styles Element
 *
 * @category   OpenDocument
 * @package    OpenDocument_Styles
 */
class OpenDocument_Styles_Element extends DOMElement
{

    /**
     * Evaluate the XPath expression
     *
     * @param  string $xpath
     * @return DOMNodeList|null
     */
    public function query($xpath)
    {
        return $this->ownerDocument->query($xpath, $this);
    }
}