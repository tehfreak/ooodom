<?php

/**
 * OpenDocument Content Element
 *
 * @category   OpenDocument
 * @package    OpenDocument_Content
 */
class OpenDocument_Content_Element extends DOMElement
{

    /**
     * Check for compliance with the scheme
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->ownerDocument->getSchema()->isValidChildOfElement($this, $this->parentNode);
    }
}