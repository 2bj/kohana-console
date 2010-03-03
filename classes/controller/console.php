<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Console extends Controller {
	
	public function action_index()
	{
		// is cli?
		if (Request::$protocol !== 'cli')
			die('Enabled only in cli mode!');
		
		ob_end_clean();
		
		// print all commands
		$console = Console::factory();
		echo $console->command('help')->help();
		
		while (TRUE)
		{
			$input = trim($console->readline());
			$params = array_map('trim', explode(' ', $input));
			$command_name = array_shift($params);

			if ($command_name == 'exit')
				break;

			try
			{
				echo $console->command($command_name)->params($params)->run().LINE_RETURN;
			}
			catch (Exception $e)
			{
				echo __("Some error occured: :message in file: :file line :line", array(':message'=>$e->getMessage(), ':file'=>$e->getFile(), ':line'=>$e->getLine()));
			}
		}
	}
}