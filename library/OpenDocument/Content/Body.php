<?php

/**
 * The <office:body> object model
 * 
 * The <office:body> element contains the elements that represent the content of a document
 *
 * @see        http://docs.oasis-open.org/office/v1.2/cs01/OpenDocument-v1.2-cs01-part1.html#element-office_body
 * @category   OpenDocument
 * @package    OpenDocument_Content
 * @subpackage Body
 */
class OpenDocument_Content_Body extends DOMElement
{
    /**
     * @var OpenDocument_Content_Body_Text
     */
    protected $_text;
    
    /**
     * Get <office:text> object model
     *
     * @return OpenDocument_Content_Body_Text
     */
    public function getText()
    {
        if (null === $this->_text) {
            $this->registerNodeClass('DOMElement', 'OpenDocument_Content_Body_Text');
            $this->_text = $this->_xpath->query('/office:text', $this)->item(0);
            if (null === $this->_text) {
                $this->_text = $this->appendChild(
                    $this->ownerDocument->createElement('office:text')
                );
            }
            $this->registerNodeClass('DOMElement', 'OpenDocument_Content_Element');
        }
        return $this->_text;
    }
}