<?php

namespace Joomla\Frontend\Input;

/**
 * ArrayInput represents an input provided as an array.
 * Usage:
 *     $input = new ArrayInput(array('name' => 'foo', 'bar' => 'foobar'));
 */
class ArrayInput extends Input
{
	private $parameters;

	/**
	 * Constructor.
	 *
	 * @param array           $parameters An array of parameters
	 * @param InputDefinition $definition A InputDefinition instance
	 */
	public function __construct(array $parameters, InputDefinition $definition = null)
	{
		$this->parameters = $parameters;

		parent::__construct($definition);
	}

	/**
	 * Processes command line arguments.
	 */
	protected function parse()
	{
		foreach ($this->parameters as $key => $value)
		{
			if ($this->definition->hasArgument($key))
			{
				$this->arguments[$key] = $value;
			}
			elseif ($this->definition->hasOption($key))
			{
				$this->options[$key] = $value;
			}
			elseif ($this->definition->hasShortcut($key))
			{
				$key = $this->definition->getOptionForShortcut($key)->getName();
				$this->options[$key] = $value;
			}
			else
			{
				throw new \InvalidArgumentException(sprintf('The "%s" parameter is not defined.', $key));
			}
		}
	}
}
