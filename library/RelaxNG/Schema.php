<?php

class RelaxNG_Schema extends DOMDocument
{
    /**
     * Кеш определений
     *
     * @var array
     */
	public $_definitions = array(
		'-style-style' => array(
			'name' => 'style:style',
			'type' => 'element',
			'elements' => array(
				'style-text-properties' => array(
					'asserts' => array(
						array( // group
							'optional',
							array('attribute' => array('style-family', array('value', 'text')))
						),
						array( // group
							'optional',
							array('attribute' => array('style-family', array('value', 'paragraph')))
						),
					)
				),
				'style-paragraph-properties' => array(
					'asserts' => array(
						'optional',
						array('attribute' => array('style-family', array('value', 'paragraph')))
					)
				),
			)
		),
		'style-text-properties' => array(
			'name' => 'style:text-properties',
			'type' => 'element',
			'attributes' => array(
				'fo-font-variant' => array(
					'asserts' => array(
						'interleave', 'optional',
						'value' => array('normal', 'small-caps')
					)
				),
				'fo-color' => array(
					'asserts' => array(
						'interleave', 'optional',
						'value' => array(
							array('type' => 'string', 'match' => '#[0-9a-fA-F]{6}')
						)
					)
				),
			)
		),
	);

	public $xpath;

	public function __construct($filename)
	{
		parent::__construct();
		$this->load($filename);
		$this->xpath = new DOMXPath($this);
		$this->xpath->registerNamespace('rng', 'http://relaxng.org/ns/structure/1.0');
	}


    /**
     * Get <rng:defined> object model
     *
     * @param  string $name
     * @return RelaxNG_Define | null
     */
	public function getDefined($name)
	{
		$defname = str_replace(':', '-', $name);
		if (array_key_exists($defname, $this->_definitions)) {
			return $this->_definitions[$defname];
		}

		$this->registerNodeClass('DOMElement', 'RelaxNG_Define');
		
		if (strpos(':', $name)) {
			$define = $this->xpath->query(
				"//element[@name='$name']/ancestor::define|//attrubute[@name='$name']/ancestor::define"
			)->item(0);
		} else {
			$define = $this->xpath->query(
				"//define[@name='$name']"
			);
		}
		$this->registerNodeClass('DOMElement', 'DOMElement');

		if ($define) {
			return new RelaxNG_Define($define);
		}
	} 



	public function getAllowedElements($context)
	{
		$element = $this->xpath->query(
			"//rng:element[@name='{$context->nodeName}']"
		)->item(0);
		var_dump($element);
		
		// рекурсивно обходим все ссылки в поисках определений елементов
		$this->_lookupElement(null, $element);
		return $context->nodeName;
	}

	public function _lookupElement($name, $context)
	{
		$query = '//rng:element';
		if ($name) {
			$query = $query .'[@name="'. $name. '"]';
		}
		var_dump($query);
		$result = $this->xpath->query($query, $context);
		if ($result->length > 0) {
			echo 'FOUND';
		}

		foreach ($this->xpath->query('rng:ref', $context) as $ref) {
			echo $ref->getAttribute('name') ."<br>";
		} 
	}
}


class RelaxNG_Define extends DOMElement
{
	public function getName()
	{
		return $this->getAttribute('name');
	}

	public function getDefinition()
	{
		return array(
			'attributes' => array(

			),
			'elements' => array(

			),
		);	
	}
}