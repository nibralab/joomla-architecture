<?php
/**
 * Test script for the Bootstrap process
 */

namespace PoC;

use Joomla\Frontend\Renderer;
use Joomla\ORM\Definition\Locator\Locator;
use Joomla\ORM\Repository\Repository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Joomla\Frontend\Renderer\Factory as RendererFactory;
use Psr\Http\Message\StreamInterface;

include "../code/Joomla/autoload.php";
include "../vendor/autoload.php";

class ContentCollector implements StreamInterface
{
	public function __construct(StreamInterface $content, Renderer $renderer) {}
	public function __toString() {}
	public function close() {}
	public function detach() {}
	public function getSize() {}
	public function tell() {}
	public function eof() {}
	public function isSeekable() {}
	public function seek($offset, $whence = SEEK_SET) {}
	public function rewind() {}
	public function isWritable() {}
	public function write($string) {}
	public function isReadable() {}
	public function read($length) {}
	public function getContents() {}
	public function getMetadata($key = null) {}
}

interface Middleware
{
	/**
	 * @param   ServerRequestInterface  $request
	 * @param   ResponseInterface       $response
	 * @param   Callable                $next
	 *
	 * @return  ResponseInterface
	 */
	public function handle(ServerRequestInterface $request, ResponseInterface $response, Callable $next);
}

class RendererMiddleware implements Middleware
{
	/**
	 * Wraps the response body with a ContentCollector
	 *
	 * The content collector is supplied with a Renderer depending on the value of the Accept: header.
	 *
	 * {@inheritdoc}
	 */
	public function handle(ServerRequestInterface $request, ResponseInterface $response, Callable $next)
	{
		return $next(
			$request,
			$response->withBody(
				new ContentCollector(
					$response->getBody(),
					(new RendererFactory)->create($request->getHeaderLine('accept'))
				)
			)
		);
	}
}

class SeoMiddleware implements Middleware
{
	/**
	 * {@inheritdoc}
	 */
	public function handle(ServerRequestInterface $request, ResponseInterface $response, Callable $next)
	{
		$values = (new Repository('seo'))->findById($request->getUri()->getPath());

		foreach ($values->keys() as $key)
		{
			$request = $request->withAttribute($key, $values->$key);
		}

		return $next($request, $response);
	}
}

class RedirectMiddleware implements Middleware
{
	/**
	 * {@inheritdoc}
	 */
	public function handle(ServerRequestInterface $request, ResponseInterface $response, Callable $next)
	{

	}
}
