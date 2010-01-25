<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Command {
	
	/*
	 * Run the command.
	 *
	 * @param 	array	params - array of the params
	 * @return 	string	message to show in console
	*/
	abstract public function run($params);
	
	/*
	 * Get help for the command
	 *
	 * @return	string	return help message about command
	*/
	public function get_help()
	{
		return "no help for the command.";
	}
}