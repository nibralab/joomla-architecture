<?php
namespace Joomla\Command;

use Joomla\Frontend\Input;
use Joomla\Frontend\Output;

class MacroCommand implements Command
{
	/** @var array Command[] */
	protected $commands = array();

	/** @var  Dispatcher */
	protected $dispatcher;

	/**
	 * @param Dispatcher $dispatcher
	 */
	public function setDispatcher(Dispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

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

	public function execute(Input $input, Output $output)
	{
		foreach ($this->commands as $command)
		{
			/** @var Command $command */
			$command->execute($input, $output);
		}
	}

	public function getName()
	{
		return 'Macro';
	}

	public function getDescription()
	{
		return 'Executes ' . implode(', ', array_keys($this->commands));
	}
}
