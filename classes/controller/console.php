<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Console extends Controller {
	
	public function action_index()
	{
		if (Request::$protocol !== 'cli')
			die('Enabled only in cli mode!');
		
		ob_end_clean();
		
		while (TRUE)
		{
			$input = trim($this->readline("\n>> "));
			$params = array_map('trim', explode(' ', $input));
			$command_name = array_shift($params);
			
			if ($command_name == 'exit')
				break;
			
			echo Console::run_command($command_name, $params, empty($params) ? 'get_help' : 'run');
		}
	}

	protected function readline($prompt)
	{
		if (extension_loaded('readline'))
		{
			$input = readline($prompt);
			readline_add_history($input);
			return $input;
		}
		else
		{
			echo $prompt;
			return fgets(STDIN);
		}
	}
}