<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class Command_Orm extends Command {

	protected $_map = array(
		'-g' => 'group',
		'-m' => 'module',
		'-d' => 'driver',
		'-t' => 'table',
		'-c' => 'class',
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
	 * @var string name of the class
	*/
	public $class = '';

	/*
	 * @var string module name in which save the model
	*/
	public $module = '';

	/*
	 * @var string name of the driver. Can be orm, sprig, hive, jelly
	*/
	public $driver = 'orm';

	public function params($params)
	{
		parent::params($params);

		// get table name and class name
		$this->table OR $this->table = array_shift($this->_params);

		return $this;
	}

	public function run()
	{
		// get table name and class name
		$this->class OR $this->class = inflector::singular($this->table);

		// validate params
		$this->check();

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
			'class' => $this->class,
		);

		$model_text = View_Console::factory('orm/'.$this->driver, $data)->render();

		// define directory in which we save file
		if ($this->module)
		{
			$modules = Kohana::modules();
			$dir = $modules[$this->module];
		} else
			$dir = APPPATH;
		$dir = $dir.'classes'.DIRECTORY_SEPARATOR.'model';

		// save file and return result
		$this->_console->save_file($dir, $this->class.EXT, $model_text);
	}

	/*
	 * Return possible drivers for the command
	 *
	 * @return Array
	*/
	public function drivers()
	{
		$drivers = array(
			'orm',
			'hive',
			'sprig',
			'jelly',
		);
		return $drivers;
	}

	/*
	 * Add rules to validate object
	*/
	public function add_rules(Validate $validate)
	{
		$validate->rule('group', 'Command::check_dbgroup');
		$validate->rule('group', 'not_empty');
		$validate->rule('module', 'Command::check_module');
		$validate->rule('driver', 'in_array', array($this->drivers()));
		$validate->rule('table', 'Command::check_table', array($this->group));
		$validate->rule('table', 'not_empty');
	}
}