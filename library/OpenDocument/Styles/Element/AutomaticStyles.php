<?php

/**
 * The <office:automatic-styles> object model
 *
 * @category   OpenDocument
 * @package    OpenDocument_Styles
 * @subpackage Element
 */
class OpenDocument_Styles_Element_AutomaticStyles extends DOMElement
{
    /**
     * Get style
     *
     * @param  string $name
     * @return OpenDocument_Styles_Element_Style|null
     */
    public function getStyle($name)
    {
        require_once 'OpenDocument/Styles/Element/Style.php';
        $this->ownerDocument->registerNodeClass('DOMElement', 'OpenDocument_Styles_Element_Style');
        $style = $this->ownerDocument->query('style:style[@style:name="'. $name .'"]', $this)->item(0);
        return $style;
    }

    /**
     * Create style
     *
     * @param  string $name
     * @return OpenDocument_Styles_Element_Style
     */
    public function createStyle($name)
    {
        require_once 'OpenDocument/Styles/Element/Style.php';
        $this->ownerDocument->registerNodeClass('DOMElement', 'OpenDocument_Styles_Element_Style');
        $style = $this->appendChild(
            $this->ownerDocument->createElement('style:style')
        );
        $style->setName($name);
        return $style;
    }
}