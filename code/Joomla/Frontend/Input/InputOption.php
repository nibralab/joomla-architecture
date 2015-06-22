<?php

namespace Joomla\Frontend\Input;

/**
 * Represents a command line option.
 */
class InputOption
{
	const VALUE_NONE = 1;
	const VALUE_REQUIRED = 2;
	const VALUE_OPTIONAL = 4;
	const VALUE_IS_ARRAY = 8;

	private $name;
	private $shortcut;
	private $mode;
	private $default;
	private $description;
	private $filter;

	/**
	 * Constructor.
	 *
	 * @param string       $name        The option name
	 * @param string|array $shortcut    The shortcuts, can be null, a string of shortcuts delimited by | or an array of shortcuts
	 * @param int          $mode        The option mode: A combination of the VALUE_* constants
	 * @param callable     $filter      A filter to validate and/or sanitize the value
	 * @param string       $description A description text
	 * @param mixed        $default     The default value (must be null for self::VALUE_REQUIRED or self::VALUE_NONE)
	 *
	 * @api
	 */
	public function __construct($name, $shortcut = null, $mode = null, Callable $filter = null, $description = '', $default = null)
	{
		$this->setName($name);
		$this->setShortcut($shortcut);
		$this->setMode($mode);
		$this->setFilter($filter);
		$this->setDescription($description);
		$this->setDefault($default);
	}

	/**
	 * Returns the option shortcut.
	 *
	 * @return string The shortcut
	 */
	public function getShortcut()
	{
		return $this->shortcut;
	}

	/**
	 * Returns the option name.
	 *
	 * @return string The name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns true if the option accepts a value.
	 *
	 * @return bool true if value mode is not self::VALUE_NONE, false otherwise
	 */
	public function acceptsValue()
	{
		return $this->isValueRequired() || $this->isValueOptional();
	}

	/**
	 * Returns true if the option requires a value.
	 *
	 * @return bool true if value mode is self::VALUE_REQUIRED, false otherwise
	 */
	public function isValueRequired()
	{
		return $this->isModeFlagSet(self::VALUE_REQUIRED);
	}

	/**
	 * Returns true if the option takes an optional value.
	 *
	 * @return bool true if value mode is self::VALUE_OPTIONAL, false otherwise
	 */
	public function isValueOptional()
	{
		return $this->isModeFlagSet(self::VALUE_OPTIONAL);
	}

	/**
	 * Returns true if the option can take multiple values.
	 *
	 * @return bool true if mode is self::VALUE_IS_ARRAY, false otherwise
	 */
	public function isArray()
	{
		return $this->isModeFlagSet(self::VALUE_IS_ARRAY);
	}

	/**
	 * Sets the default value.
	 *
	 * @param mixed $default The default value
	 *
	 * @throws \LogicException When incorrect default value is given
	 */
	public function setDefault($default = null)
	{
		if ($this->isModeFlagSet(self::VALUE_NONE) && null !== $default)
		{
			throw new \LogicException('Cannot set a default value when using InputOption::VALUE_NONE mode.');
		}

		if ($this->isArray())
		{
			if (null === $default)
			{
				$default = array();
			}
			elseif (!is_array($default))
			{
				throw new \LogicException('A default value for an array option must be an array.');
			}
		}

		$this->default = $this->acceptsValue() ? $default : false;
	}

	/**
	 * Returns the default value.
	 *
	 * @return mixed The default value
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * Returns the description text.
	 *
	 * @return string The description text
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Returns the filter function.
	 *
	 * @return Callable The filter function
	 */
	public function getFilter()
	{
		return $this->filter;
	}

	/**
	 * Checks whether the given option equals this one.
	 *
	 * @param InputOption $option option to compare
	 *
	 * @return bool
	 */
	public function equals(InputOption $option)
	{
		return $option->getName() === $this->getName()
			   && $option->getShortcut() === $this->getShortcut()
			   && $option->getDefault() === $this->getDefault()
			   && $option->isArray() === $this->isArray()
			   && $option->isValueRequired() === $this->isValueRequired()
			   && $option->isValueOptional() === $this->isValueOptional();
	}

	/**
	 * @param $name
	 */
	private function setName($name)
	{
		if (0 === strpos($name, '--'))
		{
			$name = substr($name, 2);
		}

		if (empty($name))
		{
			throw new \InvalidArgumentException('An option name cannot be empty.');
		}

		$this->name = $name;
	}

	/**
	 * @param $shortcut
	 */
	private function setShortcut($shortcut)
	{
		if (empty($shortcut))
		{
			$shortcut = null;
		}

		if (null !== $shortcut)
		{
			if (is_array($shortcut))
			{
				$shortcut = implode('|', $shortcut);
			}
			$shortcuts = preg_split('{(\|)-?}', ltrim($shortcut, '-'));
			$shortcuts = array_filter($shortcuts);
			$shortcut  = implode('|', $shortcuts);

			if (empty($shortcut))
			{
				throw new \InvalidArgumentException('An option shortcut cannot be empty.');
			}
		}

		$this->shortcut = $shortcut;
	}

	/**
	 * @param $mode
	 */
	private function setMode($mode)
	{
		if (null === $mode)
		{
			$mode = self::VALUE_NONE;
		}
		elseif (!is_int($mode) || $mode > 15 || $mode < 1)
		{
			throw new \InvalidArgumentException(sprintf('Option mode "%s" is not valid.', $mode));
		}

		$this->mode = $mode;

		if ($this->isArray() && !$this->acceptsValue())
		{
			throw new \InvalidArgumentException('Impossible to have an option mode VALUE_IS_ARRAY if the option does not accept a value.');
		}
	}

	/**
	 * @param $filter
	 */
	private function setFilter(Callable $filter)
	{
		if (null === $filter)
		{
			$filter = function($value) {
				return $value;
			};
		}
		$this->filter = $filter;
	}

	/**
	 * @param $description
	 */
	private function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @param $flag
	 *
	 * @return bool
	 */
	private function isModeFlagSet($flag)
	{
		return $flag === ($flag & $this->mode);
	}
}
