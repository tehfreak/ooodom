<?php

/**
 * The <style:style> object model
 * 
 * The <style:style> element represents styles.
 *
 * @see        http://docs.oasis-open.org/office/v1.2/cs01/OpenDocument-v1.2-cs01-part1.html#element-style_style
 * @category   OpenDocument
 * @package    OpenDocument_Styles
 * @subpackage Element
 */
class OpenDocument_Styles_Element_Style extends DOMElement
{
    /**
     * @var boolean
     */
	protected $_autoUpdate = false;

    /**
     * @var string
     */
	protected $_class;

    /**
     * @var family
     */
	protected $_family;

	public function setFamily($value)
	{
		$this->setAttribute('style:family', $value);
		return $this;
	}

	public function setName($value)
	{
		$this->setAttribute('style:name', $value);
		return $this;
	}

	public function setParentStyle($value)
	{
		if ($value instanceof OpenDocument_Styles_Element_Style) {
			$value = $value->getName();
		}
		/* DEBUG */ assert(is_string($value));
		$this->setAttribute('style:parent-style-name', $value);
		return $this;
	}

	public function setProperties($values)
	{
		
	}

	public function setProperty($name, $value)
	{
		if (false === strpos(':', $name)) {
			// имя свойства указано без префикса
		}
		foreach ($this->getAllowedElements() as $name) {
			// style:paragraph-properties
			// style:text-properties
		}
	}
}