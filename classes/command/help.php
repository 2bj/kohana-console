<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class Command_Help extends Command {

	/*
	 * @var string command name
	*/
	public $command = '';

	public function run()
	{
		// get command name
		$this->command OR $this->command = array_shift($this->_params);

		// for empty params show help
		if (empty($this->command))
			return $this->help();

		// run help
		return $this->_console->command($this->command)->help();
	}

	/*
	 * print available commands
	 *
	 * @return array information about commands
	 */
	public function help()
	{
		// get list of commands
		$commands = $this->_console->commands();

		// init
		$count = 1;
		$res = array();

		$res[] = __('Available commands:');
		foreach ($commands as $c)
			$res[] = $count++.'. '.$c;
		$res[] = __('For more information type help <command> or exit for quit console.');

		return $res;
	}
}
