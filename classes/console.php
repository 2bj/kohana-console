<?php defined('SYSPATH') OR die('No direct access allowed.');

class Console {
	
	public static $prompt = "\n>> ";
	
	public static function run_command($command_name, $params, $func = 'run')
	{
		$result = '';
		
		$class_name = 'Command_'.utf8::ucfirst($command_name);
		
		try
		{
			$class = new ReflectionClass($class_name);
			
			if ($class->isAbstract())
			{
				throw new Kohana_Exception('Cannot create instances of abstract :command',
					array(':command' => $command));
			}
			
			// Create a new instance of the command
			$command = $class->newInstance();
			$result = $class->getMethod($func)->invokeArgs($command, array($params));
		}
		catch (ReflectionException $e)
		{
			$result = "Command not found: ".$command_name;
		}
		catch (Exception $e)
		{
			$result = "Some error occured: ".$e->getMessage()." in file: ".$e->getFile()." line ".$e->getLine();
		}
		
		return $result;
	}
	
	public static function get_commands()
	{
		$commands = array();
		
		foreach (Kohana::list_files ('classes/command') as $key=>$file)
			$commands[] = basename ($file, EXT);
		
		return $commands;
	}
	
	public static function readline()
	{
		$input = '';
		if (extension_loaded('readline'))
		{
			$input = readline(Console::$prompt);
			readline_add_history($input);
		}
		else
		{
			echo Console::$prompt;
			$input = fgets(STDIN);
		}
		
		return trim($input);
	}
	
	public static function dialog($message, $choices)
	{
		echo $message;
		
		$res = '';
		while (TRUE)
		{
			$res = self::readline();
			if (in_array($res, $choices))
				break;
			
			echo "Type ".implode(',', $choices);
		}
		
		return $res;
	}
}