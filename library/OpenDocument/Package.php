<?php

/**
 * OpenDocument Package
 *
 * @see        http://docs.oasis-open.org/office/v1.2/cs01/OpenDocument-v1.2-cs01-part3.html
 * @category   OpenDocument
 * @package    OpenDocument_Package
 */
class OpenDocument_Package
{
    /**
     * @var OpenDocument_Manifest
     */
    protected $_manifest = null;

    /**
     * @var OpenDocument_Content
     */
    protected $_content = null;

    /**
     * @var OpenDocument_Styles
     */
    protected $_styles = null;

    /**
     * @var OpenDocument_Meta
     */
    protected $_meta = null;


    /**
     * @var string
     */
    protected $_path;

    /**
     * @var ZipArchive
     */
    protected $_zip = null;


    /**
     * Create package
     *
     * @param  string $path
     */
    public function __construct($path = null, $config = null)
    {
        if (null === $path) {
            $this->_path = tempnam(sys_get_temp_dir(), 'ODF');
        }
        $this->_zip = new ZipArchive();
        $this->_zip->open($this->_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
    }

    /**
     * Delete package
     */
    public function __destruct()
    {
        $this->_zip->close();
        unlink($this->_path);
    }


    /**
     * Set <manifest:manifest> object model
     *
     * @param  DOMDocument $manifest
     * @return OpenDocument_Package
     */
    public function setManifest(DOMDocument $manifest)
    {
        if ($manifest instanceof OpenDocument_Package_Manifest) {
            $this->_manifest = $manifest;
        } else {
            $this->getManifest()->loadXML(
                $manifest->saveXML()
            );
        }
        return $this;
    }

    /**
     * Get <manifest:manifest> object model
     *
     * @return OpenDocument_Package_Manifest
     */
    public function getManifest()
    {
        if (null === $this->_manifest) {
            $this->_manifest = new OpenDocument_Package_Manifest();
        }
        return $this->_manifest;
    }


    /**
     * Set <office:document-content> object model
     *
     * @param  DOMDocument $content
     * @return OpenDocument_Package
     */
    public function setContent(DOMDocument $content)
    {
        if ($content instanceof OpenDocument_Content) {
            $this->_content = $content;
        } else {
            $this->getContent()->loadXML(
                $content->saveXML()
            );
        }
        return $this;
    }

    /**
     * Get <office:document-content> object model
     *
     * @return OpenDocument_Content
     */
    public function getContent()
    {
        if (null === $this->_content) {
            $this->_content = new OpenDocument_Content();
        }
        return $this->_content;
    }


    /**
     * Set <office:document-styles> object model
     *
     * @param  DOMDocument $styles
     * @return OpenDocument_Package
     */
    public function setStyles(DOMDocument $styles)
    {
        if ($styles instanceof OpenDocument_Styles) {
            $this->_styles = $styles;
        } else {
            $this->getStyles()->loadXML(
                $styles->saveXML()
            );
        }
        return $this;
    }

    /**
     * Get <office:document-styles> object model
     *
     * @return OpenDocument_Styles
     */
    public function getStyles()
    {
        if (null === $this->_styles) {
            $this->_styles = new OpenDocument_Styles();
        }
        return $this->_styles;
    }


    /**
     * Set <office:document-meta> object model
     *
     * @param  DOMDocument $meta
     * @return OpenDocument_Package
     */
    public function setMeta(DOMDocument $meta)
    {
        if ($meta instanceof OpenDocument_Meta) {
            $this->_meta = $meta;
        } else {
            $this->getMeta()->loadXML(
                $meta->saveXML()
            );
        }
        return $this;
    }

    /**
     * Get <office:document-meta> object model
     *
     * @return OpenDocument_Meta
     */
    public function getMeta()
    {
        if (null === $this->_meta) {
            $this->_meta = new OpenDocument_Meta();
        }
        return $this->_meta;
    }


    /**
     * Add a file to the package from path
     *
     * @param  string $realpath
     * @param  string $path
     * @param  string $mime
     * @return boolean
     */
    public function addFile($realpath, $path = null, $mime = null)
    {
        if (false === $realpath = realpath($realpath)) {
            throw new Exception('File not found');
        }
        if (false === $content = file_get_contents($realpath)) {
            throw new Exception('File access denied');
        }
        if (null === $path) {
            $path = basename($realpath);
        }
        return $this->addFileFromString($path, $content, $mime);
    }

    /**
     * Add a file to the package using its contents
     *
     * @param  string $path
     * @param  string $content
     * @param  string $mime
     * @return boolean
     */
    public function addFileFromString($path, $content, $mime = null)
    {
        if ($result = $this->_zip->addFromString($path, $content)) {
            $this->getManifest()->addFile($path, $mime);
        }
        return $result;
    }
}