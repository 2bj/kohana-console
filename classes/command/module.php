<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Module extends Command {
	
	public function run($params)
	{
		$module = array_shift($params);
		
		$dir = MODPATH.$module;
		
		if (is_dir($dir))
			return 'Error: module exists';
		else {
			mkdir($dir.DIRECTORY_SEPARATOR.'classes', 644, TRUE);
			return "Create module '$module' in directory - $dir";
		}
	}
	
	public function get_help()
	{
		return <<<EOD
Simply create module(just created directory classes in module)
usage <module_name>

EOD;
	}
}