<?php
namespace Joomla\Command;

use Joomla\Frontend\Input;
use Joomla\Frontend\Output;

class Controller
{
	/** @var  CommandProcessor */
	protected $processor;

	/** @var array Command[] */
	protected $commands = array();

	/** @var  Dispatcher */
	protected $dispatcher;

	public function add(Command $command)
	{
		$this->commands[$command->getName()] = $command;
	}

	public function addCommands(array $commands)
	{
		foreach ($commands as $command)
		{
			$this->add($command);
		}
	}

	public function run(Input $input, Output $output)
	{
		// Determine command name from input
		$commandName = 'CommandNameFromInput';

		// Setup command, if needed

		// Execute command
		$this->processor->executeCommand(
			$this->commands[$commandName],
			$input,
			$output
		);
	}
}
