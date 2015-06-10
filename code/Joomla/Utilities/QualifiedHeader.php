<?php
namespace Joomla\Utilities;

class QualifiedHeader
{
	private $header;
	private $separator;
	private $wildcard;

	public function __construct($header, $separator, $wildcard)
	{
		if (preg_match('~^[\w-]+:\s+(.*)$~i', $header, $match))
		{
			$header = $match[1];
		}
		$this->header    = $header;
		$this->separator = '~' . preg_quote($separator) . '~';
		$this->wildcard  = $wildcard;
	}

	private function parseHeader($header)
	{
		$directives     = preg_split('~\s*,\s*~', $header);
		$acceptedRanges = array();

		foreach ($directives as $directive)
		{
			$parts = preg_split('~\s*;\s*~', $directive);
			$spec  = array('token' => array_shift($parts));

			while (!empty($parts))
			{
				$parts2 = preg_split('~\s*=\s*~', array_shift($parts));
				if (!isset($parts2[1]))
				{
					$parts2[1] = true;
				}
				$spec[$parts2[0]] = $parts2[1];
			}

			if (!isset($spec['q']))
			{
				$spec['q'] = 1.0;
			}
			$spec['q'] += count($spec) / 100;

			$acceptedRanges[] = $spec;
		}

		return $acceptedRanges;
	}

	/**
	 * @param $availableRanges
	 *
	 * @return mixed
	 */
	public function getBestMatch($availableRanges)
	{
		$acceptedRanges = $this->parseHeader($this->header);

		$matching = array('q' => 0.0);
		foreach ($availableRanges as $range)
		{
			$available = $this->split($range);
			foreach ($acceptedRanges as $acceptedRange)
			{
				$accepted = $this->split($acceptedRange['token']);

				if (!$this->match($available[0], $accepted[0]))
				{
					continue;
				}

				if (!$this->match($available[1], $accepted[1]))
				{
					continue;
				}

				if ($matching['q'] < $acceptedRange['q'])
				{
					$matching          = $acceptedRange;
					$matching['token'] = $range;
				}
			}
		}

		return $matching;
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	private function split($value)
	{
		$result = preg_split($this->separator, $value, 2);
		if (!isset($result[1]))
		{
			$result[1] = $this->wildcard;
		}

		return $result;
	}

	/**
	 * @param $var1
	 * @param $var2
	 *
	 * @return bool
	 */
	private function match($var1, $var2)
	{
		return $var1 == $this->wildcard || $var2 == $this->wildcard || $var1 == $var2;
	}
}
