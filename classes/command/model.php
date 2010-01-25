<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Model extends Command {
	
	public function run($options)
	{
		$table = array_shift($options);
		
		$group = arr::get($options, '-g', 'default');
		$class = 'Column_'.Kohana::config('database.'.$group.'.type');
		$driver = new $class;
		
		$columns = $driver->get_columns($table, $group);
		
		$data = array(
			'columns' => $columns,
			'options' => $options,
			'table' => $table,
			'group' => $group,
		);
		
		$model_text = View::factory('console/model', $data)->render();
		
		if (isset($options['-m']))
			$dir = MODPATH.$options['-m'].DIRECTORY_SEPARATOR;
		else
			$dir = APPPATH;
		
		$dir = $dir.'classes'.DIRECTORY_SEPARATOR.'model';
		if ( ! is_dir($dir))
			mkdir($dir, 644, TRUE);
		
		$file = $dir.DIRECTORY_SEPARATOR.inflector::singular($table).EXT;
		
		if (is_file($file) AND Console::dialog('File '.$file.' exists. Overwrite? (yes|no)', array('yes', 'no')) != 'yes')
			return "Nothing created.";
		
		file_put_contents ($file, $model_text);
		
		return "Create file $file";
	}
	
	public function get_help()
	{
		return <<<EOD
Create sprig model for table.
Usage: model <table_name> [-m <module_name>] [-g <database_group>]

-m <module_name> - create file in the input module
-g <database_group> - use group to connect to database

EOD;
	}
}