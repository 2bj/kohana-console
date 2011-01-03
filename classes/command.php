<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

abstract class Command {

	/*
	 * @var Console link to the object console
	*/
	protected $_console = NULL;

	/*
	 * @var Array unnamed params
	*/
	protected $_params = array();

	/*
	 * @var Array this param define associations
	*/
	protected $_map = array();
	
	/*
	 * Create command object
	 * @param console instance of the class Console
	*/
	public function __construct($console)
	{
		$this->_console = $console;
	}
	
	/*
	 * Set params for command
	*/
	public function params($params)
	{
		// convert string -f param1 -a param2 into array
		// -f => param1
		// -a => param2
		$last_param = NULL;
		foreach ($params as $key=>$p)
		{
			if (preg_match('#-[a-z]+#i', $p))
			{
				$last_param = $p;
				unset($params[$key]);

				$param = arr::get($this->_map, $last_param);
				if ($param AND isset($this->{$param}))
					$this->{$param} = TRUE;
			} else if ($last_param) {
				$last_param = NULL;
				unset($params[$key]);

				$param = arr::get($this->_map, $last_param);
				if ($param AND isset($this->{$param}))
					$this->{$param} = $p;
			}
		}

		$this->_params = $params;
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