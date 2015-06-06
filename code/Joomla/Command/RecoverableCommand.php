<?php
namespace Joomla\Command;

interface RecoverableCommand extends Command
{
	public function undo();
	public function redo();
}
