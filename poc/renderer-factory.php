<?php

include '../code/Joomla/Frontend/Renderer.php';
include '../code/Joomla/Frontend/Renderer/Factory.php';
include '../code/Joomla/Frontend/Renderer/NotFoundException.php';
include '../code/Joomla/Frontend/Http/Header/QualifiedHeader.php';
include '../code/Joomla/Frontend/Http/Header/AcceptHeader.php';

$factory = new \Joomla\Frontend\Renderer\Factory();

$acceptHeaders = array(
	'Accept: text/*;q=0.3, text/html;q=0.7, text/html;level=1, text/html;level=2;q=0.4, */*;q=0.5',
	'Accept: audio/*; q=0.2, audio/basic',
	'text/plain; q=0.5, text/html, text/x-dvi; q=0.8, text/x-c',
	'application/xml',
	'application/xml;q=0.8, application/json; q=0.9',
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
);

foreach ($acceptHeaders as $a)
{
	try
	{
		$renderer = $factory->create($a);
		echo "\n$a\n";
		print_r($renderer);
	}
	catch (\Joomla\Frontend\Renderer\NotFoundException $e)
	{
		echo "\n{$e->getMessage()}\n\n";
	}
}
