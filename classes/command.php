<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Command {
	
	public $console = NULL;
	
	protected $_params = array();
	
	protected $_named = array();
	
	/*
	 * Create command object
	 * @param console instance of the class Console
	*/
	public function __construct($console)
	{
		$this->console = $console;
	}
	
	/*
	 * Set params for command
	*/
	public function params($params)
	{
		$this->_params = $params;
		return $this;
	}
	
	/*
	 * Set named params for the command
	*/
	public function named($named)
	{
		$this->_named = $named;
		return $this;
	}
	
	/*
	 * Run the command.
	 *
	 * @return 	string	message to show in console
	*/
	abstract public function run();
	
	/*
	 * Get help for the command
	 *
	 * @return	string	return help message about command
	*/
	public function help()
	{
		$parts = explode('_', get_class($this));
		$command = utf8::strtolower(array_pop($parts));
		
		$help_file = 'help/'.I18n::$lang.'/'.$command;
		
		if (Kohana::find_file('views', $help_file))
			return View::factory($help_file)->render();
		else
			return __("No help for the command :command", array(':command'=>$command));
	}
}