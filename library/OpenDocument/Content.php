<?php

/**
 * OpenDocument Content
 *
 * @see        http://docs.oasis-open.org/office/v1.2/cs01/OpenDocument-v1.2-cs01-part1.html#element-office_document-content
 * @category   OpenDocument
 * @package    OpenDocument_Content
 */
class OpenDocument_Content extends DOMDocument
{
    /**
     * @var OpenDocument_Package
     */
    protected $_package = null;

    /**
     * @var RelaxNG_Schema
     */
    protected $_schema = null;


    /**
     * @var OpenDocument_Styles_Element_AutomaticStyles
     */
    protected $_automaticStyles = null;


    /**
     * @var DOMXPath
     */
    protected $_xpath;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('1.0', 'utf-8');
        $root = $this->appendChild(
            $this->createElementNS(
                'urn:oasis:names:tc:opendocument:xmlns:office:1.0',
                'office:document-content'
            )
        );
        $root->setAttribute('office:version', '1.2');
        
        require_once 'OpenDocument/Content/Element.php';
        $this->registerNodeClass('DOMElement', 'OpenDocument_Content_Element');
    }


    /**
     * Evaluate the XPath expression
     *
     * @param  string $query
     * @param  DOMNode $context
     * @return DOMNodeList|null
     */
    public function query($query, DOMNode $context = null)
    {
        if (null === $this->_xpath) {
            $this->_xpath = new DOMXPath($this);
        }
        return $this->_xpath->query($query, $context);
    }


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


    /**
     * Get schema
     *
     * @return RelaxNG_Schema
     */
    public function getSchema()
    {
        if (null === $this->_schema) {
            require_once 'RelaxNG/Schema.php';
            $this->_schema = new RelaxNG_Schema();
        }
        return $this->_schema;
    }


    /**
     * Get <office:automatic-styles> object model
     *
     * @return OpenDocument_Styles_Element_AutomaticStyles
     */
    public function getAutomaticStyles()
    {
        if (null === $this->_automaticStyles) {
            require_once 'OpenDocument/Styles/Element/AutomaticStyles.php';
            $this->registerNodeClass('DOMElement', 'OpenDocument_Styles_Element_AutomaticStyles');
            $this->_automaticStyles = $this->query('office:automatic-styles', $this->documentElement)->item(0);
            if (null === $this->_automaticStyles) {
                $this->_automaticStyles = $this->documentElement->appendChild(
                    $this->createElement('office:automatic-styles')
                );
            }
            $this->registerNodeClass('DOMElement', 'OpenDocument_Content_Element');
        }
        return $this->_automaticStyles;
    }
}