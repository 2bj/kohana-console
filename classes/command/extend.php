<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Extend extends Command {

	public function run($params)
	{
		$class = strtolower(array_shift($params));
		$parts = expolode('_', $class);
		$class = array_pop($parts);
		$class_dir = implode(DIRECTORY_SEPARATOR, $parts);
		
		$directory = arr::get($params, '-d', 'kohana');
		
		$file = Kohana::find_file('classes'.DIRECTORY_SEPARATOR.$class_dir, $class, EXT);
		
		return $result;
	}

	public function get_help()
	{
		return <<<EOD
extend class:
usage: extend <class> [-d <directory>]

EOD;
	}
}