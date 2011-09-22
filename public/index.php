<?php
error_reporting(-1);
set_include_path(get_include_path() .PATH_SEPARATOR. realpath('C:\Users\d3z\projects\OpenDocument Object Model\library'));

require 'RelaxNG/Schema.php';
$schema = new RelaxNG_Schema('OpenDocument-v1.2-cs01-schema.rng');


$input = new DOMDocument();
$input->load('input.xml');
$xpath = new DOMXPath($input);


$style = $xpath->query('//style:style[@style:name="lol"]')->item(0);

var_dump($schema->getAllowedElements($style));