<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Model extends Command {

	public $group = 'default';

	public $table = '';

	public $module = '';

	public function run($options)
	{
		$this->table OR $this->table = array_shift($options);
		if (empty($this->table))
			return $this->help();

		$class = 'Column_'.Kohana::config('database.'.$this->group.'.type');
		$driver = new $class;

		$columns = $driver->get_columns($table, $group);

		$data = array(
			'columns' => $columns,
			'options' => $options,
			'table' => $table,
			'group' => $group,
		);

		$model_text = View::factory('console/model', $data)->render();

		if ($this->module)
		{
			$modules = Kohana::modules();
			$dir = MODPATH.$modules[$this->module].DIRECTORY_SEPARATOR;
		} else
			$dir = APPPATH;

		$dir = $dir.'classes'.DIRECTORY_SEPARATOR.'model';
		echo $this->console->save_file($dir, inflector::singular($table), $model_text);
	}
}