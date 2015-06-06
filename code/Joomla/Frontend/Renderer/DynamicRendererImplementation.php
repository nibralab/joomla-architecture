<?php
namespace Joomla\Frontend;

trait DynamicRendererImplementation
{
	private $handlers = array();

	public function registerContentType($type, Callable $handler)
	{
		$this->handlers[strtolower($type)] = $handler;
	}

	public function __call($method, $arguments)
	{
		if (preg_match('~^visit(.+)~', $method, $match))
		{
			$type = strtolower($match[1]);
			if (!isset($this->handlers[$type]))
			{
				$type = 'default';
			}
			if (isset($this->handlers[$type]))
			{
				$handler = $this->handlers[$type];
				$this->output .= $handler($arguments[0]);
			}
			else
			{
				echo "\nLogWarn: Unknown content type {$match[1]}, no default\n";
			}
		}
	}
}
