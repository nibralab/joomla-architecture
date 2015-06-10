<?php
namespace Joomla\Frontend\Renderer;

class NotFoundException extends \InvalidArgumentException
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
