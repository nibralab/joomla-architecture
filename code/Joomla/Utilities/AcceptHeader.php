<?php
namespace Joomla\Utilities;

class AcceptHeader extends QualifiedHeader
{
	public function __construct($header)
	{
		parent::__construct($header, '/', '*');
	}
}
