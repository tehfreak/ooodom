<?php

/**
 * @see OpenDocument_Styles_Element
 */
require_once 'OpenDocument/Styles/Element.php';


/**
 * Abstract style object model
 *
 * @category   OpenDocument
 * @package    OpenDocument_Styles
 * @subpackage Element
 */
abstract class OpenDocument_Styles_Element_AbstractStyle extends OpenDocument_Styles_Element
{
    /**
     * @var array
     */
    protected $_allowedContainers = null;


    /**
     * Get value of style:family attribute
     *
     * @return string
     */
    public function getFamily()
    {
        return $this->getAttribute('style:family');
    }

    /**
     * Set value of style:family attribute
     *
     * @return OpenDocument_Styles_Element_AbstractStyle
     */
    public function setFamily($family)
    {
        $this->setAttribute('style:family', $family);
        return $this;
    }


    /**
     * Check for exists container
     *
     * @return boolean
     */
    public function hasContainer($family)
    {
        if ($this->_isAllowedContainer($family)) {
            return (boolean) $this->query('style:'. $family .'-properties')->length;
        }
        return false;
    }

    /**
     * Get allowed container with given family
     *
     * @param  string $family
     * @return OpenDocument_Content_Element_StyleContainer|null
     */
    public function getContainer($family)
    {
        if ($this->_isAllowedContainer($family)) {
            if (!array_key_exists($family, $this->_containers)) { // кеш пуст
                $this->ownerDocument->registerNodeClass('DOMElement', 'OpenDocument_Styles_Element_StyleContainer');
                $this->_containers[$family] = $this->query('style:'. $family .'-properties')->item(0);
                if (null === $this->_containers[$family]) {
                    $this->_containers[$family] = $this->appendChild(
                        $this->ownerDocument->createElement('style:'. $family .'-properties')
                    );
                }
            }
            assert($this->_containers[$family] instanceof OpenDocument_Styles_Element_StyleContainer);
            return $this->_containers[$family];
        }
    }

    /**
     * Get all allowed containers
     *
     * @return array
     */
    public function getContainers()
    {
        $containers = array();
        foreach ($this->_getAllowedContainers() as $allowed) {
            if ($container = $this->getContainer($allowed)) {
                $containers[$container->nodeName] = $container;
            }
        }
        return $containers;
    }

    /**
     * Get iterator
     *
     * @return array
     */
    public function getIterator()
    {
        return $this->getContainers();
    }


    /**
     * Get allowed containers config
     *
     * @return array
     */
    protected function _getAllowedContainers()
    {
        if (null === $this->_allowedContainers) {
            $allowedContainers = array(
                'text' => array('text'),
                'paragraph' => array('paragraph', 'text'),
                'section' => array('section'),
                'table-cell' => array('table-cell', 'paragraph', 'text'),
                'table-row' => array('table-row'),
                'table-column' => array('table-column'),
                'table' => array('table'),
            );
            if (!array_key_exists($family = $this->getFamily(), $allowedContainers)) {
                throw new Exception('Rules for family "'. $family. '" not found');
            }
            $this->_allowedContainers = $allowedContainers[$family];
        }
        return $this->_allowedContainers;
    }

    /**
     * Check is allowed container with given family
     *
     * @param  string $family
     * @return boolean
     */
    protected function _isAllowedContainer($family)
    {
        return in_array($family, $this->_getAllowedContainers());
    }
}