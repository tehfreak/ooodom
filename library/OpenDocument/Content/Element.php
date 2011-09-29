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
     * @var OpenDocument_Styles_Element_Style
     */
    protected $_style = null;

    /**
     * Check for compliance with the scheme
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->ownerDocument->getSchema()->isValidChildOfElement($this, $this->parentNode);
    }


    /**
     * Set <style:style> object model
     *
     * @return OpenDocument_Content_Element
     */
    public function setStyle(OpenDocument_Styles_Element_Style $style)
    {
        $this->setAttribute(
            $this->prefix .':style-name', $style->getName()
        );
        $this->_style = $style;
        return $this;
    }

    /**
     * Get <style:style> object model
     *
     * @return OpenDocument_Styles_Element_Style
     */
    public function getStyle()
    {
        if (null === $this->_style) {
            if ($name = $this->getAttribute($this->prefix .':style-name')) {
                $styles = $this->ownerDocument->getAutomaticStyles();
                if (null === $this->_style = $styles->getStyle($name)) {
                    /*
                    $this->_style = $styles->createStyle();
                    $this->_style->setName($name);
                    $this->_style->setFamily(
                        $this->ownerDocument->getSchema()->getStyleFamily
                    );
                    */
                }
            }
        }
        return $this->_style;
    }
}