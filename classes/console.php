<?php defined('SYSPATH') OR die('No direct access allowed.');

class Console {
	
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
			
			// Create a new instance of the controller
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
}