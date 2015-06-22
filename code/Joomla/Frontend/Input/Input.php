<?php

namespace Joomla\Frontend\Input;

/**
 * Input is the base class for all concrete Input classes.
 */
abstract class Input implements InputInterface
{
	/** @var InputDefinition */
	protected $definition;

	/** @var array  */
	protected $options = array();

	/** @var array  */
	protected $arguments = array();

	/** @var bool  */
	protected $interactive = true;

	/**
	 * Constructor.
	 *
	 * @param InputDefinition $definition A InputDefinition instance
	 */
	public function __construct(InputDefinition $definition = null)
	{
		if (null === $definition)
		{
			$this->definition = new InputDefinition();
		}
		else
		{
			$this->bind($definition);
			$this->validate();
		}
	}

	/**
	 * Binds the current Input instance with the given arguments and options.
	 *
	 * @param InputDefinition $definition A InputDefinition instance
	 */
	protected function bind(InputDefinition $definition)
	{
		$this->arguments  = array();
		$this->options    = array();
		$this->definition = $definition;

		$this->parse();
	}

	/**
	 * Processes command line arguments.
	 */
	abstract protected function parse();

	/**
	 * Validates the input.
	 *
	 * @throws \RuntimeException When not enough arguments are given
	 */
	protected function validate()
	{
		if (count($this->arguments) < $this->definition->getArgumentRequiredCount())
		{
			throw new \RuntimeException('Not enough arguments.');
		}

		foreach ($this->arguments as $name => $value)
		{
			$this->arguments[$name] = call_user_func($this->definition->getArgument($name)->getFilter(), $value);
		}

		foreach ($this->options as $name => $value)
		{
			$this->options[$name] = call_user_func($this->definition->getOption($name)->getFilter(), $value);
		}
	}

	/**
	 * Returns true if an InputArgument or InputOption object exists by name or position.
	 *
	 * @param string|int $name The InputArgument name or position or the InputOption name
	 *
	 * @return bool true if the InputArgument or InputOption object exists, false otherwise
	 */
	public function has($name)
	{
		return $this->hasArgument($name) || $this->hasOption($name);
	}

	/**
	 * Returns the value of a parameter (option or argument).
	 *
	 * @param string $name    The name of the option or argument
	 * @param mixed  $default The default value to return if the parameter does not exist
	 *
	 * @return mixed The parameter value
	 */
	public function get($name, $default = false)
	{
		if ($this->hasOption($name))
		{
			return $this->getOption($name);
		}

		if ($this->hasArgument($name))
		{
			return $this->getArgument($name);
		}

		return $default;
	}

	/**
	 * Checks if the input is interactive.
	 *
	 * @return bool Returns true if the input is interactive
	 */
	public function isInteractive()
	{
		return $this->interactive;
	}

	/**
	 * Sets the input interactivity.
	 *
	 * @param bool $interactive If the input should be interactive
	 */
	public function setInteractive($interactive)
	{
		$this->interactive = (bool)$interactive;
	}

	/**
	 * Returns the argument value for a given argument name.
	 *
	 * @param string $name The argument name
	 *
	 * @return mixed The argument value
	 * @throws \InvalidArgumentException When argument given doesn't exist
	 */
	protected function getArgument($name)
	{
		if (!$this->definition->hasArgument($name))
		{
			throw new \InvalidArgumentException(sprintf('The "%s" argument does not exist.', $name));
		}

		return isset($this->arguments[$name]) ? $this->arguments[$name]
			: $this->definition->getArgument($name)->getDefault();
	}

	/**
	 * Sets an argument value by name.
	 *
	 * @param string $name  The argument name
	 * @param string $value The argument value
	 *
	 * @throws \InvalidArgumentException When argument given doesn't exist
	 */
	protected function setArgument($name, $value)
	{
		if (!$this->definition->hasArgument($name))
		{
			throw new \InvalidArgumentException(sprintf('The "%s" argument does not exist.', $name));
		}

		$this->arguments[$name] = $value;
	}

	/**
	 * Returns true if an InputArgument object exists by name or position.
	 *
	 * @param string|int $name The InputArgument name or position
	 *
	 * @return bool true if the InputArgument object exists, false otherwise
	 */
	protected function hasArgument($name)
	{
		return $this->definition->hasArgument($name);
	}

	/**
	 * Returns the option value for a given option name.
	 *
	 * @param string $name The option name
	 *
	 * @return mixed The option value
	 * @throws \InvalidArgumentException When option given doesn't exist
	 */
	protected function getOption($name)
	{
		if (!$this->definition->hasOption($name))
		{
			throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
		}

		return isset($this->options[$name]) ? $this->options[$name] : $this->definition->getOption($name)->getDefault();
	}

	/**
	 * Sets an option value by name.
	 *
	 * @param string      $name  The option name
	 * @param string|bool $value The option value
	 *
	 * @throws \InvalidArgumentException When option given doesn't exist
	 */
	protected function setOption($name, $value)
	{
		if (!$this->definition->hasOption($name))
		{
			throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
		}

		$this->options[$name] = $value;
	}

	/**
	 * Returns true if an InputOption object exists by name.
	 *
	 * @param string $name The InputOption name
	 *
	 * @return bool true if the InputOption object exists, false otherwise
	 */
	protected function hasOption($name)
	{
		return $this->definition->hasOption($name);
	}

	/**
	 * Escapes a token through escapeshellarg if it contains unsafe chars.
	 *
	 * @param string $token
	 *
	 * @return string
	 */
	protected function escapeToken($token)
	{
		return preg_match('{^[\w-]+$}', $token) ? $token : escapeshellarg($token);
	}
}
