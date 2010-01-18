<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Help extends Command {
	
	public function run($params)
	{
		$command_name = trim(array_shift($params));
		$result = Console::run_command($command_name, $params, 'get_help');
		
		return $result;
	}
	
	public function get_help()
	{
		$result = <<<EOD
usage: help <command>

Available commands:
EOD;
		$commands = Console::get_commands();

		$count = 1;
		foreach ($commands as $c)
			$result .= $count++.'. '.$c."\n";

		return $result;
	}
}