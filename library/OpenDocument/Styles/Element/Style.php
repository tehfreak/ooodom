<?php

/**
 * The <style:style> object model
 *
 * @category   OpenDocument
 * @package    OpenDocument_Styles
 * @subpackage Element
 */
class OpenDocument_Styles_Element_Style extends DOMElement
{
    /**
     * Set style:name attribute value
     *
     * @param  string $value
     * @return OpenDocument_Styles_Element_Style
     */
    public function setName($value)
    {
        $this->setAttribute('style:name', $value);
        return $this;
    }

    /**
     * Get style:name attribute value
     *
     * @return string|null
     */
    public function getName()
    {
        if ($this->hasAttribute('style:name')) {
            return $this->getAttribute('style:name');
        } else {
            return null;
        }
    }


    /**
     * Set style:family attribute value
     *
     * @param  string $value
     * @return OpenDocument_Styles_Element_Style
     */
    public function setFamily($value)
    {
        $this->setAttribute('style:family', $value);
        // обходит контейнеры, невалидные удаляет
        foreach ($this->ownerDocument->query('*', $this) as $container) {
            if (!$container->isValid()) {
                $this->removeChild($container);
            }
        }

        return $this;
    }

    /**
     * Get style:family attribute value
     *
     * @return string|null
     */
    public function getFamily()
    {
        if ($this->hasAttribute('style:family')) {
            return $this->getAttribute('style:family');
        } else {
            return null;
        }
    }
}