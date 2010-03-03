<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Help extends Command {
	
	public function run()
	{
		if (empty($this->_params))
			return $this->help();
		
		$command_name = trim(array_shift($this->_params));
		$result = $this->console->command($command_name)->help();
		
		return $result;
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