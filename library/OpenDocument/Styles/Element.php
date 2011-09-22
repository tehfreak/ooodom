<?php

class OpenDocument_Styles_Element extends DOMElement
{
	public function getScheme()
	{
		return $this->ownerDocument->getScheme();
	}

	public function isValidAttribute($name, $value)
	{
		return $this->getScheme()->isValidAttribute($name, $value, $this);
	}

	public function isValidChild(DOMElement $child)
	{
		return $this->getScheme()->isValidChild($child, $this);
	}
}