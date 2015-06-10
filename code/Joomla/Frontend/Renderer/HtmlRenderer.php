<?php
namespace Joomla\Frontend\Renderer;

use Joomla\Frontend\Renderer;
use Joomla\Frontend\Http\Client\ScriptStrategy;

class HtmlRenderer extends Renderer
{
	/** @var  ScriptStrategy */
	private $clientScript;

	public function setScriptStrategy(ScriptStrategy $strategy)
	{
		$this->clientScript = $strategy;
	}
}
