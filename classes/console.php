<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

abstract class Console {

	public static $instances = array();

	public function __construct(array $config = array())
	{
		// copy config values to the class
		foreach ($config as $key=>$value)
			$this->{$key} = $value;
	}

	/*
	 * Factory of the class
	*/
	public static function instance($type = 'cli')
	{
		// Normalize to prevent duplicates
		$type = strtolower($type);

		if (empty(self::$instances[$type]))
		{
			// load configuration
			$config = Kohana::config('console')->get($type);

			//set class name
			$class = 'Console_'.utf8::ucfirst($type);

			// create a new console instance
			self::$instances[$type] = new $class($config);
		}

		return self::$instances[$type];
	}

	/*
	 * Get instance of the command by command name
	 *
	 * @params string command name
	 *
	 * @return Command instance of the command
	*/
	public function command($command_name)
	{
		// set command name
		$class_name = 'Command_'.utf8::ucfirst($command_name);
		$class = new ReflectionClass($class_name);

		// we can not create abstract class, so throw an exception
		if ($class->isAbstract())
		{
			throw new Kohana_Exception('Cannot create instances of abstract :command',
				array(':command' => $command_name));
		}

		// Create a new instance of the command
		return $class->newInstance($this);
	}

	/**
	 * Exec the command from the console, like
	 *   $string = 'commandname -p param1 -p param2';
	 *   $command = Console::instance()->exec($string);
	 *
	 * @param string string to execute
	 *
	 * @return  Command		instance of command
	 */
	public function exec($input)
	{
		// make array of params from the input
		$params = array_map('trim', explode(' ', $input));

		// first elements in the array must be command name
		$command_name = array_shift($params);

		// run command with params
		return $this->command($command_name)->params($params)->run();
	}

	/**
	 * Find end returns all commands in directory classes/command
	 *
	 * @return  array	array of command classes
	 */
	public function commands()
	{
		// array of commands
		$commands = array();

		// fill the array of commands
		// this need to rewrite? for supporting commands in subfolders
		foreach (Kohana::list_files ('classes/command') as $key=>$file)
			$commands[] = basename ($file, EXT);

		return $commands;
	}

	abstract public function print_line($text, $return = TRUE);
}