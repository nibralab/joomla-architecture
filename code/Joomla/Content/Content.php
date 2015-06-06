<?php
namespace Joomla\Content;

use Joomla\Frontend\Renderer;

interface Content
{
	public function accept(Renderer $renderer);
}
