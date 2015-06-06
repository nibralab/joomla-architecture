<?php
namespace Joomla\Content;

interface ContentGroup extends Content
{
	/**
	 * @return Content[]
	 */
	public function getChildren();
}
