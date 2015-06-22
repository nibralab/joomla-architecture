<?php

namespace Joomla\Frontend\Input;

/**
 * Represents a command line argument.
 */
class InputArgument
{
	const REQUIRED = 1;
	const OPTIONAL = 2;
	const IS_ARRAY = 4;

	private $name;
	private $mode;
	private $default;
	private $description;
	private $filter;

	/**
	 * Constructor.
	 *
	 * @param string   $name        The argument name
	 * @param int      $mode        The argument mode: self::REQUIRED or self::OPTIONAL
	 * @param callable $filter      A filter to validate and/or sanitize the value
	 * @param string   $description A description text
	 * @param mixed    $default     The default value (for self::OPTIONAL mode only)
	 *
	 * @throws \InvalidArgumentException When argument mode is not valid
	 * @api
	 */
	public function __construct($name, $mode = null, Callable $filter = null, $description = '', $default = null)
	{
		$this->setName($name);
		$this->setMode($mode);
		$this->setFilter($filter);
		$this->setDescription($description);
		$this->setDefault($default);
	}

	/**
	 * Returns the argument name.
	 *
	 * @return string The argument name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns true if the argument is required.
	 *
	 * @return bool true if parameter mode is self::REQUIRED, false otherwise
	 */
	public function isRequired()
	{
		return $this->isModeFlagSet(self::REQUIRED);
	}

	/**
	 * Returns true if the argument can take multiple values.
	 *
	 * @return bool true if mode is self::IS_ARRAY, false otherwise
	 */
	public function isArray()
	{
		return $this->isModeFlagSet(self::IS_ARRAY);
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
		if ($this->isModeFlagSet(self::REQUIRED) && null !== $default)
		{
			throw new \LogicException('Cannot set a default value except for InputArgument::OPTIONAL mode.');
		}

		if ($this->isArray())
		{
			if (null === $default)
			{
				$default = array();
			}
			elseif (!is_array($default))
			{
				throw new \LogicException('A default value for an array argument must be an array.');
			}
		}

		$this->default = $default;
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
	 * @param $name
	 */
	private function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param $mode
	 */
	private function setMode($mode)
	{
		if (null === $mode)
		{
			$mode = self::OPTIONAL;
		}
		elseif (!is_int($mode) || $mode > 7 || $mode < 1)
		{
			throw new \InvalidArgumentException(sprintf('Argument mode "%s" is not valid.', $mode));
		}

		$this->mode = $mode;
	}

	/**
	 * @param $filter
	 */
	private function setFilter(Callable $filter)
	{
		if (null === $filter)
		{
			$filter = function ($value)
			{
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
		$isSet = $flag === ($flag & $this->mode);

		return $isSet;
}
}
