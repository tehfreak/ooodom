<?php

/**
 * @see OpenDocument_Package
 */
require_once 'OpenDocument/Package.php';


/**
 * OpenDocument Factory
 *
 * @see        http://docs.oasis-open.org/office/v1.2/cs01/OpenDocument-v1.2-cs01-part1.html#__RefHeading__440344_826425813
 * @category   OpenDocument
 * @package    OpenDocument
 */
class OpenDocument
{
    const TEXT          = 'application/vnd.oasis.opendocument.text';
    const TEXT_TEMPLATE = 'application/vnd.oasis.opendocument.text-template';
    const TEXT_MASTER   = 'application/vnd.oasis.opendocument.text-master';

    /**
     * Factory package
     *
     * @param  string $mimetype
     * @param  mixed $config
     * @return OpenDocument_Package
     */
    public static function factory($mimetype, $config = null)
    {
        switch ($mimetype) {
            case OpenDocument::TEXT:
            case OpenDocument::TEXT_TEMPLATE:
            case OpenDocument::TEXT_MASTER:
                return new OpenDocument_Package($mimetype, $config);
        }
        throw new Exception('Not implemented');
    }
}