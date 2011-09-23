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
    protected $_zipPath = null;

    /**
     * @var ZipArchive
     */
    protected $_zip = null;


    /**
     * Construct package
     *
     * @param  string $path
     * @param  array $config
     */
    public function __construct($path = null, $config = null)
    {
        $this->_zipPath = $path;
    }

    /**
     * Destruct package
     */
    public function __destruct()
    {
        if ($this->_zip) {
            $this->_zip->close();
        }
    }

    /**
     * Get opened zip archive
     *
     * @return ZipArchive
     */
    public function getZip()
    {
        if (null === $this->_zip) {
            $zip = new ZipArchive();
            if (null === $this->_zipPath) {
                $this->_zipPath = tempnam(sys_get_temp_dir(), 'ODF');
            }
            if (!$result = $zip->open($this->_zipPath, ZIPARCHIVE::CREATE)) {
                throw new Exception('Cannot open zip, error '. $result);
            }
            $this->_zip = $zip;
        }
        return $this->_zip;
    }


    /**
     * Load state from zip archive
     *
     * @return OpenDocument_Package
     */
    public function load($path)
    {
        $this->_zip = null;
        $this->_zipPath = $path;

        $this->_manifest = null;
        if ($manifest = $this->getZip()->getFromName('META-INF/manifest.xml')) {
            $this->getManifest()->loadXML($manifest);
        }

        $this->_content = null;
        if ($content = $this->getZip()->getFromName('content.xml')) {
            $this->getContent()->loadXML($content);
        }

        $this->_styles = null;
        if ($styles = $this->getZip()->getFromName('styles.xml')) {
            $this->getStyles()->loadXML($styles);
        }

        $this->_meta = null;
        if ($meta = $this->getZip()->getFromName('meta.xml')) {
            $this->getMeta()->loadXML($meta);
        }
    }


    /**
     * Set <manifest:manifest> object model
     *
     * @param  OpenDocument_Package_Manifest $manifest
     * @return OpenDocument_Package
     */
    public function setManifest(OpenDocument_Package_Manifest $manifest)
    {
        $this->_manifest = $manifest;
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
            require_once 'OpenDocument/Package/Manifest.php';
            $this->setManifest(new OpenDocument_Package_Manifest());
        }
        return $this->_manifest;
    }


    /**
     * Set <office:document-content> object model
     *
     * @param  OpenDocument_Content $content
     * @return OpenDocument_Package
     */
    public function setContent(OpenDocument_Content $content)
    {
        $this->_content = $content->setPackage($this);
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
            require_once 'OpenDocument/Content.php';
            $this->setContent(new OpenDocument_Content());
        }
        return $this->_content;
    }


    /**
     * Set <office:document-styles> object model
     *
     * @param  OpenDocument_Styles $styles
     * @return OpenDocument_Package
     */
    public function setStyles(OpenDocument_Styles $styles)
    {
        $this->_styles = $styles->setPackage($this);
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
            require_once 'OpenDocument/Styles.php';
            $this->setStyles(new OpenDocument_Styles());
        }
        return $this->_styles;
    }


    /**
     * Set <office:document-meta> object model
     *
     * @param  OpenDocument_Meta $meta
     * @return OpenDocument_Package
     */
    public function setMeta(OpenDocument_Meta $meta)
    {
        $this->_meta = $meta->setPackage($this);
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
            require_once 'OpenDocument/Meta.php';
            $this->setMeta(new OpenDocument_Meta());
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
        if ($result = $this->getZip()->addFromString($path, $content)) {
            $this->getManifest()->addFile($path, $mime);
        }
        return $result;
    }
}