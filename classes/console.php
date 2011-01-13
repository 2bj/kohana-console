<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class Console {

	/*
	 * @var Console Instance of the class
	*/
	public static $instance = NULL;

	/*
	 * Factory of the class
	*/
	public static function instance()
	{
		if (empty(self::$instance))
		{
			// create a new console instance
			self::$instance = new self;
		}

		return self::$instance;
	}

	/*
	 * Array of the generated files
	 * key of the array is md5 hash of the full path to the file
	 * value is the instance of the class File_Console
	*/
	protected $_files = array();

	/*
	 * Function that save file
	*/
	public function save_file($dir, $file, $text)
	{
		$fullname = $dir.DIRECTORY_SEPARATOR.$file;
		$hash = md5($fullname);
		if ( ! isset($this->_files[$hash]))
		{
			$this->_files[$hash] = new File_Console($fullname, $text);
		}
	}

	/*
	 * Return generated files
	 *
	 * @return Array array of generated files
	*/
	public function files()
	{
		return $this->_files;
	}

	/*
	 * Save all generated files
	 *
	 * @return Console
	*/
	public function save()
	{
		foreach ($this->_files as $hash=>$f)
			$f->save();

		return $this;
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
}