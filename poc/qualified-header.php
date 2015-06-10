<?php

include '../code/Joomla/Utilities/QualifiedHeader.php';

$testData = array(
	'Accept' => array(
		'separator' => '/',
		'wildcard' => '*',
		'headers' => array(
			'Accept: text/*;q=0.3, text/html;q=0.7, text/html;level=1, text/html;level=2;q=0.4, */*;q=0.5',
			'Accept: audio/*; q=0.2, audio/basic',
			'text/plain; q=0.5, text/html, text/x-dvi; q=0.8, text/x-c',
			'application/xml',
			'application/xml;q=0.8, application/json; q=0.9',
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
		),
		'available' => array(
			'text/plain',
			'text/ansi',
			'application/xml',
			'application/json',
			'text/html',
			'application/pdf',
			'application/docbook+xml',
			'application/vnd.oasis.docbook+xml',
			'application/x-docbook',
		)
	),
	'Accept-Language' => array(
		'separator' => '-',
		'wildcard'  => '',
		'headers'   => array(
			'Accept-Language: de,en-US;q=0.7,en;q=0.3',
		),
		'available' => array(
			'en-GB',
			'de-DE',
		)
	),
);

foreach ($testData as $headerType)
{
	foreach ($headerType['headers'] as $header)
	{
		try
		{
			$obj = new \Joomla\Utilities\QualifiedHeader($header, $headerType['separator'], $headerType['wildcard']);
			echo "\n$header\n";
			print_r($obj->getBestMatch($headerType['available']));
		} catch (\Exception $e)
		{
			echo "\n{$e->getMessage()}\n\n";
		}
	}
}
