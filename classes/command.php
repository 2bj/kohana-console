<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Command {
	
	/*
	 * run the command.
	 * @param params - array of the params
	 * @return message to show in console
	*/
	abstract public function run($params);
	
	/*
	 * get help for the command
	*/
	public function get_help()
	{
		return "no help for the command.";
	}
}