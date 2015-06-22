<?php

namespace Joomla\Frontend\Input;

/**
 * InputInterface is the interface implemented by all input classes.
 */
interface InputInterface
{
	/**
	 * Returns true if an InputArgument or InputOption object exists by name or position.
	 *
	 * @param string|int $name The InputArgument name or position or the InputOption name
	 *
	 * @return bool true if the InputArgument or InputOption object exists, false otherwise
	 */
	public function has($name);

	/**
	 * Returns the value of a parameter (option or argument).
	 *
	 * @param string $name    The name of the option or argument
	 * @param mixed  $default The default value to return if no result is found
	 *
	 * @return mixed The parameter value
	 */
	public function get($name, $default = false);

	/**
	 * Is this input means interactive?
	 *
	 * @return bool
	 */
	public function isInteractive();

	/**
	 * Sets the input interactivity.
	 *
	 * @param bool $interactive If the input should be interactive
	 */
	public function setInteractive($interactive);
}
