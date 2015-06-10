<?php
namespace Joomla\Frontend;

abstract class Renderer
{
	protected $options;

	public function __construct($options)
	{
		$this->options = $options;
	}
}
