<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Console extends Controller {

	public function action_index()
	{
		// enabled console module in cli
		if (Request::$protocol !== 'cli')
			die('Enabled only in cli mode!');

		// for security: not work in production
		if (Kohana::$environment == Kohana::PRODUCTION)
			die('Production enabled');

		ob_end_clean();

		// first of all print all commands
		$console = Console::instance('cli');
		$console->print_line($console->command('help')->help());

		while (TRUE)
		{
			// read command
			$input = trim($console->readline());

			// empty command? next please
			if (empty($input))
				continue;

			if ($input == 'exit')
				break;

			try
			{
				$result = $console->exec($input);
				$console->print_line($result);
			}
			catch (Exception $e)
			{
				$error_text = __("Some error occured: :message in file: :file line :line", array(':message'=>$e->getMessage(), ':file'=>$e->getFile(), ':line'=>$e->getLine()));
				$console->print_line($error_text);
			}
		}
	}
}