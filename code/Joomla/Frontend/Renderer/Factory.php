<?php
namespace Joomla\Frontend\Renderer;

use Joomla\Utilities\AcceptHeader;

class Factory
{
	protected $mediaTypeMap = array(
		// CLI formats
		'text/plain'                        => 'PlainRenderer',
		'text/ansi'                         => 'AnsiRenderer',

		// REST formats
		'application/xml'                   => 'XmlRenderer',
		'application/json'                  => 'JsonRenderer',

		// Web/Office formats
		'text/html'                         => 'HtmlRenderer',
		'application/pdf'                   => 'PdfRenderer',

		// The DocBook format seems not to be registered. @link http://wiki.docbook.org/DocBookMimeType
		'application/docbook+xml'           => 'DocbookRenderer',
		'application/vnd.oasis.docbook+xml' => 'DocbookRenderer',
		'application/x-docbook'             => 'DocbookRenderer',
	);

	public function create($acceptHeader = '*/*')
	{
		$header = new AcceptHeader($acceptHeader);

		$match = $header->getBestMatch(array_keys($this->mediaTypeMap));
		if (!isset($match['token']))
		{
			throw(new NotFoundException("No matching renderer found for\n\t$acceptHeader"));
		}

		$filename  = __DIR__ . '/' . $this->mediaTypeMap[$match['token']] . '.php';
		$classname = __NAMESPACE__ . '\\' . $this->mediaTypeMap[$match['token']];

		if (!class_exists($classname))
		{
			include_once $filename;
		}

		return new $classname($match);
	}
}
