<?php

namespace Joomla\Frontend;

use Joomla\Frontend\Router\NotFoundException;
use Joomla\Frontend\Router\Route;

class Router
{
	/** @var  Route[] */
	protected $routes;

	protected $catchall;

	public function addGetRoute($path, Callable $callback)
	{
		$route          = new Route(array('get'), $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function addPostRoute($path, Callable $callback)
	{
		$route          = new Route(array('post'), $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function addDeleteRoute($path, Callable $callback)
	{
		$route          = new Route(array('delete'), $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function addOptionsRoute($path, Callable $callback)
	{
		$route          = new Route(array('options'), $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function addPatchRoute($path, Callable $callback)
	{
		$route          = new Route(array('patch'), $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function addPutRoute($path, Callable $callback)
	{
		$route          = new Route(array('put'), $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function addHeadRoute($path, Callable $callback)
	{
		$route          = new Route(array('head'), $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function addRoute($methods, $path, Callable $callback)
	{
		$route          = new Route($methods, $path, $callback);
		$this->routes[] = $route;

		return $route;
	}

	public function setCatchallRoute(Callable $callback)
	{
		$methods        = array('get', 'post', 'delete', 'options', 'patch', 'put', 'head');
		$path           = ':path?';
		$route          = new Route($methods, $path, $callback);
		$route->setTokens(array('path' => '.*'));
		$this->routes[] = $route;

		return $route;
	}

	public function getMatchingRoute($method, $path)
	{
		foreach ($this->routes as $route)
		{
			if ($route->match($method, $path))
			{
				return $route;
			}
		}
		if (empty($this->catchall))
		{
			throw new NotFoundException();
		}
		return $this->catchall;
	}
}
