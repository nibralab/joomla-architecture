<?php
namespace Joomla\Frontend;

class RendererNotFoundException extends \InvalidArgumentException
{
	public function __construct($frontend, $type)
	{
		if (empty($type))
		{
			$type = 'generic';
		}
		$message = "No {$type} {$frontend} renderer found";
		parent::__construct($message);
	}
}
