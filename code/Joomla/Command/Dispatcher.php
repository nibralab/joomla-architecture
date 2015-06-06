<?php
namespace Joomla\Command;

interface Dispatcher
{
	public function trigger($event, $vargs);
}
