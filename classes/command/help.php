<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Help extends Command {

	public $command = '';

	public function run()
	{
		$this->command OR $this->command = array_shift($this->_params);

		if (empty($this->command))
			return $this->help();

		return $this->console->command($this->command)->help();
	}

	public function help()
	{
		// print available commands
		$commands = $this->console->commands();
		$count = 1;
		$res = LINE_RETURN.__('Available commands:').LINE_RETURN;
		foreach ($commands as $c)
			$res .= $count++.'. '.$c.LINE_RETURN;
		$res .= LINE_RETURN.__('For more information type help <command> or exit for quit console.').LINE_RETURN;

		return $res;
	}
}
