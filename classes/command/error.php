<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class Command_Error extends Command {

	/*
	 * -f - name of file to save in
	 * -m - name module where same
	*/
	protected $_map = array(
		'-f' => 'file',
		'-m' => 'module',
		'-c' => 'class',
	);

	/*
	 * @var string name class for which generate
	*/
	public $class = '';

	/*
	 * @var string filename
	*/
	public $file = '';

	/*
	 * @var string module name
	*/
	public $module = '';

	/*
	 * @var string orm driver name
	*/
	public $_driver = 'orm';

	public function params($params)
	{
		parent::params($params);
		// get model from params
		$this->class OR $this->class = array_shift($this->_params);
	}

	/*
	 * @return string
	*/
	public function run()
	{
		$this->check();

		// name of file
		$this->file OR $this->file = $this->class.'_error';

		// define name of file and directory
		if ($this->module)
		{
			$modules = Kohana::modules();
			$dir = $modules[$module];
		} else
			$dir = APPPATH;
		$dir = $dir.'messages';

		// get driver
		$driver = self::get_driver($this->class);
		// create new model
		$class = ${$driver}::factory($this->model);

		$data = array(
			'model' => $class,
		);

		$error_text = View_Console::factory('error/'.$this->driver, $data)->render();

		//save file
		$this->_console->save_file($dir, $this->file.EXT, $error_text);
	}

	/*
	 * Add rules to validate object
	*/
	public function add_rules(Validate $validate)
	{
		$validate->rule('module', 'Command::check_module');
		$validate->rule('class', 'not_empty');
		$validate->rule('class', 'Command_Error::check_driver');
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

	public static function check_driver ($class_name)
	{
		$driver = self::get_driver($class_name);

		return ($driver == NULL);
	}

	/*
	 *
	*/
	public static function get_driver($class_name) {
		$class = new ReflectionClass($class_name);

		if( false === $class ) {
			return NULL;
		}
		do {
			$class = $class->getParentClass();
			$name = $class->getName();

			if (in_array($name, $this->drivers()))
				return $name;
		} while( false !== $class );

		return NULL;
	}
}