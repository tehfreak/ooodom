<?php

/**
 * OpenDocument package manifest
 *
 * @see        http://docs.oasis-open.org/office/v1.2/cs01/OpenDocument-v1.2-cs01-part3.html#__RefHeading__752825_826425813
 * @category   OpenDocument
 * @package    OpenDocument_Package
 */
class OpenDocument_Package_Manifest extends DOMDocument
{
    /**
     * @var DOMXPath
     */
    protected $_xpath;


    /**
     * Create manifest
     */
    public function __construct()
    {
        parent::__construct('1.0', 'UTF-8'); // new DOMDocument
        $root = $this->appendChild(
            $this->createElementNS(
                'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0',
                'manifest:manifest'
            )
        );
        $root->setAttribute('manifest:version', '1.2');
        $this->addFile('/', 'application/vnd.oasis.opendocument.text');

        $this->_xpath = new DOMXPath($this);
    }

    /**
     * Get string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->saveXML();
    }


    /**
     * Add <manifest:file-entry>
     *
     * @param  string $path
     * @param  string $mime
     * @return DOMElement
     */
    public function addFile($path, $mime = 'text/xml')
    {
        if (null === $element = $this->_xpath->query("manifest:file-entry[@manifest:full-path='$path']")->item(0)) {
            $element = $this->documentElement->appendChild(
                $this->createElement('manifest:file-entry')
            );
        }
        $element->setAttribute('manifest:full-path', $path);
        $element->setAttribute('manifest:media-type', $mime);
        return $element;
    }
}