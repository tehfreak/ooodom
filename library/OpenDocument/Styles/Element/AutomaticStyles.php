<?php

/**
 * @see OpenDocument_Styles_Element
 */
require_once 'OpenDocument/Styles/Element.php';


/**
 * The <office:automatic-styles> object model
 *
 * @category   OpenDocument
 * @package    OpenDocument_Styles
 * @subpackage Element
 */
class OpenDocument_Styles_Element_AutomaticStyles extends OpenDocument_Styles_Element
{
    /**
     * @var array
     */
    protected $_styles = array();


    public function hasStyle($family, $name)
    {
        $query = 'style:style[@style:name="'. $name .'"]';
        return (boolean) $this->query($query)->length;
    }

    /**
     * Get style
     *
     * @param  string $family
     * @param  string $name
     * @return OpenDocument_Styles_Element_Style
     */
    public function getStyle($family, $name)
    {
        // lookup cache
        if (isset($this->_styles[$family][$name])) {
            return $this->_styles[$family][$name];
        }

        require_once 'OpenDocument/Styles/Element/Style.php';
        $this->ownerDocument->registerNodeClass(
            'DOMElement', 'OpenDocument_Styles_Element_Style'
        );

        if ($this->hasStyle($family, $name)) {
            $style = $this->query(
                'style:style'
                . '[@style:family="'. $family .'"]'
                . '[@style:name="'. $name .'"]'
            )->item(0);
        } else {
            $style = $this->appendChild(
                $this->ownerDocument->createElement('style:style')
            );
            $style->setFamily($family)->setName($name);
        }
        return $this->_styles[$family][$name] = $style;
    }
}