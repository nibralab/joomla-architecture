<?php
namespace Joomla\Frontend\Renderer;

class Factory
{
	protected $mediaTypeMap = array(
		// CLI formats
		'text/plain'                        => 'PlainRenderer',
		'text/ansi'                         => 'AnsiRenderer',

		// REST formats
		'application/xml'                   => 'XmlRenderer',
		'application/json'                  => 'JsonRenderer',

		// Web formats
		'text/html'                         => 'HtmlRenderer',
		'application/pdf'                   => 'PdfRenderer',

		// The DocBook format seems not to be registered. @link http://wiki.docbook.org/DocBookMimeType
		'application/docbook+xml'           => 'DocbookRenderer',
		'application/vnd.oasis.docbook+xml' => 'DocbookRenderer',
		'application/x-docbook'             => 'DocbookRenderer',
	);

	public function create($acceptHeader = '*/*')
	{
		$acceptedTypes = $this->parseAcceptHeader($acceptHeader);

		$type = $this->getBestTypeMatch($acceptedTypes);
		if (empty($type))
		{
			throw(new NotFoundException("No matching renderer found for\n\t$acceptHeader"));
		}

		$filename  = __DIR__ . '/' . $this->mediaTypeMap[$type] . '.php';
		$classname = __NAMESPACE__ . '\\' . $this->mediaTypeMap[$type];

		if (!class_exists($classname))
		{
			include_once $filename;
		}

		return new $classname;
	}

	private function parseAcceptHeader($acceptHeader)
	{
		if (preg_match('~^Accept:\s+(.*)$~i', $acceptHeader, $match))
		{
			$acceptHeader = $match[1];
		}
		$types = preg_split('~\s*,\s*~', $acceptHeader);
		$acceptedTypes = array();
		foreach ($types as $type)
		{
			$parts = preg_split('~\s*;\s*~', $type);
			$typeInfo = array('type' => array_shift($parts));
			while (!empty($parts))
			{
				$parts2 = preg_split('~\s*=\s*~', array_shift($parts));
				if (!isset($parts2[1]))
				{
					$parts2[1] = true;
				}
				$typeInfo[$parts2[0]] = $parts2[1];
			}
			if (!isset($typeInfo['q']))
			{
				$typeInfo['q'] = 1.0;
			}
			$acceptedTypes[] = $typeInfo;
		}

		return $acceptedTypes;
	}

	/**
	 * @param $acceptedTypes
	 *
	 * @return mixed
	 */
	private function getBestTypeMatch($acceptedTypes)
	{
		$availableTypes = array();
		foreach (array_keys($this->mediaTypeMap) as $type)
		{
			$availableTypes[$type] = 0.0;
			foreach ($acceptedTypes as $acceptedType)
			{
				if (count($acceptedType) > 2)
				{
					// Can't handle extra values yet
					continue;
				}

				$available = explode('/', $type);
				$accepted  = explode('/', $acceptedType['type']);

				if ($available[0] != $accepted[0] && $available[0] != '*' && $accepted[0] != '*')
				{
					continue;
				}

				if ($available[1] != $accepted[1] && $available[1] != '*' && $accepted[1] != '*')
				{
					continue;
				}

				if ($availableTypes[$type] < $acceptedType['q'])
				{
					$availableTypes[$type] = $acceptedType['q'];
				}
			}
		}

		$availableTypes = array_filter($availableTypes);
		asort($availableTypes);

		$orderedTypes = array_keys($availableTypes);

		return array_pop($orderedTypes);
	}
}
