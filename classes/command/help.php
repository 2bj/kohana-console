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
		return <<<EOD
Available commands
-model
-controller
EOD;
	}
}