<?php
namespace Joomla\Frontend\Http;

class AcceptLanguageHeader extends QualifiedHeader
{
	public function __construct($header)
	{
		parent::__construct($header, '-', '');
	}
}
