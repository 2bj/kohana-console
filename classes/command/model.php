<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Model extends Command {

	public $group = 'default';

	public $table = '';

	public $module = '';

	public function run()
	{
		$this->table OR $this->table = array_shift($this->_params);
		if (empty($this->table))
			return $this->help();

		$class = 'Column_'.Kohana::config('database.'.$this->group.'.type');
		$driver = new $class;

		$data = array(
			'columns' => $driver->get_columns($this->table, $this->group),
			'table' => $this->table,
			'group' => $this->group,
		);
		
		$titles = array_intersect(array('name', 'title', 'url'), array_keys($data['columns']));
		if ( ! empty($titles) AND is_array($titles))
			$data['title_key'] = array_shift($titles);

		$model_text = View::factory('console/model', $data)->render();

		if ($this->module)
		{
			$modules = Kohana::modules();
			$dir = MODPATH.$modules[$this->module].DIRECTORY_SEPARATOR;
		} else
			$dir = APPPATH;

		$dir = $dir.'classes'.DIRECTORY_SEPARATOR.'model';
		echo $this->console->save_file($dir, inflector::singular($this->table).EXT, $model_text);
	}
}