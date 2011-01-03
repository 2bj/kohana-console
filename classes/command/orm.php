<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class Command_Orm extends Command {

	protected $_map = array(
		'-g' => 'group',
		'-m' => 'module',
	);

	/*
	 * @var string name of the database group to connect with
	*/
	public $group = 'default';

	/*
	 * @var string name of the database table
	*/
	public $table = '';

	/*
	 * @var string module name in which save the model
	*/
	public $module = '';

	/*
	 * @var string name of the driver. Can be orm, sprig, hive, jelly
	*/
	public $driver = 'orm';

	public function run()
	{
		// Show help if no table specified
		$this->table OR $this->table = array_shift($this->_params);
		if (empty($this->table))
			return $this->help();

		// get driver for the current db
		$type = Kohana::config('database.'.$this->group.'.type');

		// create column class
		$class = 'Column_'.utf8::ucfirst($type);
		$driver = new $class;

		// get columns information
		$columns = $driver->get_columns($this->table, $this->group);

		// try to guess title column
		$titles = array_intersect(array('name', 'title', 'url'), array_keys($columns));
		$title_key = NULL;
		if ( ! empty($titles) AND is_array($titles))
			$title_key = array_shift($titles);

		// search primary key and relationship
		$primary_key = NULL;
		$belongs_to = array();
		$has_many = array();
		$has_one = array();
		foreach ($columns as $name=>$info)
		{
			if ($info['type'] == 'auto')
				$primary_key = $name;
			else if ($info['type'] == 'belongsto')
				$belongs_to[$name] = $info;
		}

		// render file
		$data = array(
			'columns' => $columns,
			'title_key' => $title_key,
			'primary_key' => $primary_key,
			'belongs_to' => $belongs_to,
			'has_many' => $has_many,
			'has_one' => $has_one,
			'table' => $this->table,
			'group' => $this->group,
		);

		$model_text = View::factory('console/orm/'.$this->driver, $data)->render();

		// define directory in which we save file
		if ($this->module)
		{
			$modules = Kohana::modules();
			$dir = $modules[$this->module].DIRECTORY_SEPARATOR;
		} else
			$dir = APPPATH;
		$dir = $dir.'classes'.DIRECTORY_SEPARATOR.'model';

		// save file and return result
		echo $this->_console->save_file($dir, inflector::singular($this->table).EXT, $model_text);
	}
}