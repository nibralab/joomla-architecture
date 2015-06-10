<?php
namespace Joomla\Utilities;

class AcceptLanguageHeader extends QualifiedHeader
{
	public function __construct($header)
	{
		parent::__construct($header, '-', '');
	}
}
