<?php defined('SYSPATH') OR die('No direct access allowed.');

if (Kohana::$is_windows)
	define ('LINE_RETURN', "\r\n");
else
	define ('LINE_RETURN', "\n");

class Console {
	
	public static $prompt = "\n>> ";
	
	/*
	 * Factory of the class
	*/
	public static function factory()
	{
		return new self;
	}
	
	public function command($command_name)
	{
		$class_name = 'Command_'.utf8::ucfirst($command_name);
		$class = new ReflectionClass($class_name);
		
		if ($class->isAbstract())
		{
			throw new Kohana_Exception('Cannot create instances of abstract :command',
				array(':command' => $command_name));
		}
		
		// Create a new instance of the command
		return $class->newInstance($this);
	}
	
	/**
	 * Find end returns all commands in directory classes/command
	 *
	 * @return  array	array of command classes
	 */
	public function commands()
	{
		$commands = array();
		
		foreach (Kohana::list_files ('classes/command') as $key=>$file)
			$commands[] = basename ($file, EXT);
		
		return $commands;
	}
	
	/**
	 * Read and return the line from input
	 *
	 * @return  string	input string
	 */
	public function readline()
	{
		$input = '';
		if (extension_loaded('readline'))
		{
			$input = readline(self::$prompt);
			readline_add_history($input);
		}
		else
		{
			echo self::$prompt;
			$input = fgets(STDIN);
		}
		
		return trim($input);
	}
	
	/**
	 * Print message and wait until user input string from the choices
	 *
	 * @param	string	message to print on concole
	 * @return  array	array of the correct answers (yes, no, wait, etc)
	 */
	public function dialog($message, $choices)
	{
		echo $message;
		
		$res = '';
		while (TRUE)
		{
			$res = $this->readline();
			if (in_array($res, $choices))
				break;
			
			echo __("Type ").implode(',', $choices).LINE_RETURN;
		}
		
		return $res;
	}
	
	public function save_file($dir, $file, $text)
	{
		$file = str_replace('_', DIRECTORY_SEPARATOR, $file);
		
		$dest = $dir.DIRECTORY_SEPARATOR.$file;
		if (is_file($dest) AND $this->dialog(__('File :file exists. Overwrite? (yes|no)', array(':file'=>$dest)), array('yes', 'no')) != 'yes')
			return __('Nothing created');

		if ( ! is_dir(dirname($dest)))
			mkdir(dirname($dest), 644, TRUE);
		
		file_put_contents ($dest, $text);
		
		return __('Create file :file', array(':file'=>$file));
	}
}