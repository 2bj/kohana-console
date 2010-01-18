<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Console extends Controller {
	
	public function action_index()
	{
		// is cli?
		if (Request::$protocol !== 'cli')
			die('Enabled only in cli mode!');

		ob_end_clean();

		// print available commands
		$commands = Console::get_commands();
		$count = 1;
		echo "Available commands:\n";
		foreach ($commands as $c)
			echo $count++.'. '.$c."\n";
		echo "For more information type help <command> or exit for quit console.";
		
		while (TRUE)
		{
			$input = trim(Console::readline());
			$params = array_map('trim', explode(' ', $input));
			$command_name = array_shift($params);
			
			// convert string -f param1 -a param2 into array
			// -f => param1
			// -a => param2
			$last_param = NULL;
			foreach ($params as $p)
			{
				if (preg_match('#-[a-z]+#i', $p))
				{
					$last_param = $p;
					$params[$p] = '';
				} else if ($last_param) {
					$params[$last_param] = $p;
					$last_param = NULL;
				}
			}
			
			if ($command_name == 'exit')
				break;
			
			echo Console::run_command($command_name, $params, empty($params) ? 'get_help' : 'run');
		}
	}
}