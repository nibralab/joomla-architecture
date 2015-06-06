<?php
namespace Joomla\Command;

use Joomla\Frontend\Input;
use Joomla\Frontend\Output;

interface Command
{
	public function getName();
	public function getDescription();
	public function execute(Input $input, Output $output);
	public function setDispatcher(Dispatcher $dispatcher);
}
