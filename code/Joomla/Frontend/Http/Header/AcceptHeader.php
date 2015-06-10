<?php
namespace Joomla\Frontend\Http;

class AcceptHeader extends QualifiedHeader
{
	public function __construct($header)
	{
		parent::__construct($header, '/', '*');
	}
}
