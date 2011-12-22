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
     * @var string
     */
    protected $_mimetype = null;


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
     * @var ZipArchive
     */
    protected $_zip = null;


    /**
     * Construct package
     *
     * @param  string $mimetype
     * @param  array $config
     */
    public function __construct($mimetype, array $config = null)
    {
        $this->_mimetype = $mimetype;
        $this->setConfig($config);
    }

    /**
     * Set config
     *
     * @param  mixed $config
     * @return OpenDocument_Package
     */
    public function setConfig($config)
    {
        if (is_array($config)) {
            
        }
        return $this;
    }

    /**
     * Destruct package
     */
    public function __destruct()
    {
        $this->closeZip();
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
     * @todo Check zip archive
     *
     * @param  string $path
     * @return boolean
     */
    public function checkZip($path)
    {
        if (!realpath($path)) {
            return false;
        }
        $zip = new ZipArchive();
        if (true !== $zip->open($path, ZIPARCHIVE::CHECKCONS)) {
            return false;
        }
        return true;
    }

    /**
     * Get opened zip archive
     *
     * @param  string $path
     * @return ZipArchive
     */
    public function getZip($path = null)
    {
        if (null === $this->_zip) {
            $zip = new ZipArchive();

            $path = ($path) ? $path : tempnam(sys_get_temp_dir(), 'ODF');
            if (!$result = $zip->open($path, ZIPARCHIVE::CREATE)) {
                throw new Exception('Cannot open zip', $result);
            }
            $this->_zip = $zip;
        }
        return $this->_zip;
    }

    /**
     * Close zip
     *
     * @return OpenDocument_Package
     */
    public function closeZip()
    {
        if ($this->_zip) {
            if ($this->_zip->filename) {
                $this->_zip->close();
            }
        }
        $this->_zip = null;
        return $this;
    }


    /**
     * Add a file to the package from path
     *
     * @param  string $realpath
     * @param  string $path
     * @param  string $mimetype
     * @return boolean
     */
    public function addFile($realpath, $path = null, $mimetype = null)
    {
        if (null === $realpath = realpath($realpath)) {
            throw new Exception('File not found');
        }
        if (null === $path) {
            $path = basename($realpath);
        }
        if ($result = $this->getZip()->addFile($realpath, $path)) {
            if ($mimetype) {
                $this->getManifest()->addFile($path, $mimetype);
            }
        }
        return $result;
    }

    /**
     * Add a file to the package using its contents
     *
     * @param  string $path
     * @param  string $string
     * @param  string $mimetype
     * @return boolean
     */
    public function addFileFromString($path, $string, $mimetype = null)
    {
        if ($result = $this->getZip()->addFromString($path, $string)) {
            if ($mimetype) {
                $this->getManifest()->addFile($path, $mimetype);
            }
        }
        return $result;
    }

    /**
     * Retrieve the file contents from the package using its path
     *
     * Returns NULL if the file is not declared in the manifest, or returns FALSE if the file not exists in package
     *
     * @param  string $path
     * @return string|null|false
     */
    public function getFile($path)
    {
        if ($this->getManifest()->hasFile($path)) {
            return $this->getZip()->getFromName($path);
        }
    }


    /**
     * Load state from zip archive
     *
     * @param  string $path
     * @return OpenDocument_Package
     */
    public function load($path)
    {
        $zip = $this->closeZip()->getZip($path);

        $this->_manifest = null;
        if ($manifest = $zip->getFromName('META-INF/manifest.xml')) {
            $this->getManifest()->loadXML($manifest);
        }

        $this->_content = null;
        if ($content = $zip->getFromName('content.xml')) {
            $this->getContent()->loadXML($content);
        }

        $this->_styles = null;
        if ($styles = $zip->getFromName('styles.xml')) {
            $this->getStyles()->loadXML($styles);
        }

        $this->_meta = null;
        if ($meta = $zip->getFromName('meta.xml')) {
            $this->getMeta()->loadXML($meta);
        }
    }

    /**
     * Save state to zip archive
     *
     * @param  null|string $path
     * @return OpenDocument_Package
     */
    public function save($path = null)
    {
        if ($path) {
            $this->closeZip()->getZip($path);
        }

        $this->addFileFromString(
            'mimetype', $this->_mimetype
        );
        $this->addFileFromString(
            'META-INF/manifest.xml', $this->getManifest()->saveXML(), 'text/xml'
        );
        $this->addFileFromString(
            'content.xml', $this->getContent()->saveXML(), 'text/xml'
        );
        $this->addFileFromString(
            'styles.xml', $this->getStyles()->saveXML(), 'text/xml'
        );
        if ($this->_meta) {
            $this->addFileFromString(
                'meta.xml', $this->getMeta()->saveXML(), 'text/xml'
            );
        }

        $path = $this->getZip()->filename;
        $this->getZip()->close();

        return $path;
    }
}