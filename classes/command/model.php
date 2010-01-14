<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Model extends Command {
	
	public function run($options)
	{
		$table = array_shift($options);
		
		$db = Database::instance();
		$data = array(
			'columns' => $db->list_columns($table),
			'options' => $options,
			'table' => $table,
		);
		
		return Kohana::debug($db->list_columns($table));
		
		return View::factory('console/model', $data);
	}
	
	public function get_help()
	{
		$result = <<<EOD
Usage: model <table_name>
EOD;
	}
}