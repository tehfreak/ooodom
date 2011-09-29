<?php

/**
 * OpenDocument Styles
 *
 * @see        http://docs.oasis-open.org/office/v1.2/cs01/OpenDocument-v1.2-cs01-part1.html#element-office_document-styles
 * @category   OpenDocument
 * @package    OpenDocument_Styles
 */
class OpenDocument_Styles extends DOMDocument
{
    /**
     * @var OpenDocument_Package
     */
    protected $_package = null;


    /**
     * Set parent package
     *
     * @param  OpenDocument_Package $package
     * @return OpenDocument_Content
     */
    public function setPackage(OpenDocument_Package $package)
    {
        $this->_package = $package;
        return $this;
    }

    /**
     * Get parent package
     *
     * @return OpenDocument_Package
     */
    public function getPackage()
    {
        if (null === $this->_package) {
            require_once 'OpenDocument/Package.php';
            $this->_package = new OpenDocument_Package();
            $this->_package->setContent($this);
        }
        return $this->_package;
    }
}