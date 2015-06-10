<?php

namespace Joomla\Frontend\Router;

class Route
{
	/** @var array */
	protected $methods = array();

	/** @var  string */
	protected $path;

	/** @var  Callable */
	protected $callback;

	/** @var  array */
	protected $tokens;

	/** @var  array */
	protected $values = array();

	/** @var  array */
	protected $parameters = array();

	public function __construct($methods, $path, Callable $callback)
	{
		$this
			->setMethods(array_map('strtoupper', $methods))
			->setPath($path)
			->setCallback($callback);
	}

	public function match($method, $path)
	{
		if (!$this->matchMethod($method))
		{
			return false;
		}

		return $this->matchPath($path);
	}

	public function getParameters()
	{
		return array_merge($this->parameters, $this->values);
	}

	/**
	 * @param array $methods
	 *
	 * @return $this
	 */
	public function setMethods($methods)
	{
		$this->methods = $methods;

		return $this;
	}

	/**
	 * @param string $path
	 *
	 * @return $this
	 */
	public function setPath($path)
	{
		$this->path = $path;

		return $this;
	}

	/**
	 * @param Callable $callback
	 *
	 * @return $this
	 */
	public function setCallback(Callable $callback)
	{
		$this->callback = $callback;

		return $this;
	}

	/**
	 * @return Callable
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
	 * @param array $tokens
	 *
	 * @return $this
	 */
	public function setTokens($tokens)
	{
		$this->tokens = $tokens;

		return $this;
	}

	/**
	 * @param array $values
	 *
	 * @return $this
	 */
	public function setValues($values)
	{
		$this->values = $values;

		return $this;
	}

	/**
	 * @param string $part
	 *
	 * @return bool
	 */
	private function isParameter($part)
	{
		return $part[0] == ':';
	}

	/**
	 * @param string $part
	 *
	 * @return bool
	 */
	private function isOptional($part)
	{
		return substr($part, -1) == '?';
	}

	/**
	 * @param $token
	 *
	 * @return string
	 */
	private function getTokenPattern($token)
	{
		if (!isset($this->tokens[$token]))
		{
			$this->tokens[$token] = '[^/]+';
		}

		return '(?<' . $token . '>' . $this->tokens[$token] . ')';
	}

	private function getPathPattern()
	{
		$patterns = array();
		$suffix   = '';
		$this->parameters = array();

		foreach (explode('/', $this->path) as $pos => $part)
		{
			$token = trim($part, ':?');
			$prefix = $pos == 0 ? '' : '/';
			if ($this->isParameter($part))
			{
				$this->parameters[$token] = '';
				if ($this->isOptional($part))
				{
					$prefix = '(?:' . $prefix;
					$suffix .= ')?';
				}
				$patterns[] = $prefix . $this->getTokenPattern($token);
			}
			else
			{
				$patterns[] = $prefix . preg_quote($part, '~');
			}
		}

		return '~^' . implode('', $patterns) . $suffix . '$~';
	}

	/**
	 * @param $method
	 *
	 * @return bool
	 */
	public function matchMethod($method)
	{
		return in_array(strtoupper($method), $this->methods);
}

	/**
	 * @param $path
	 *
	 * @return int
	 */
	public function matchPath($path)
	{
		if (preg_match($this->getPathPattern(), $path, $match))
		{
			foreach ($this->parameters as $token => $value)
			{
				if (isset($match[$token]))
				{
					$this->parameters[$token] = $match[$token];
				}
				elseif (isset($this->values[$token]))
				{
					$this->parameters[$token] = $this->values[$token];
				}
			}

			return true;
		}

		return false;
	}
}
