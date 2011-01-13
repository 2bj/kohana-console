<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

abstract class Command {

	/*
	 * @var Console link to the object console
	*/
	protected $_console = NULL;

	/*
	 * @var Array unnamed params
	*/
	protected $_params = array();

	/*
	 * @var Array this param define associations
	*/
	protected $_map = array();
	
	/*
	 * Create command object
	 * @param console instance of the class Console
	*/
	public function __construct($console)
	{
		$this->_console = $console;
	}
	
	/*
	 * Set params for command
	*/
	public function params($params)
	{
		$this->clear();

		// convert string -f param1 -a param2 into array
		// -f => param1
		// -a => param2
		$last_param = NULL;
		foreach ($params as $key=>$p)
		{
			if (preg_match('#-[a-z]+#i', $p))
			{
				$last_param = $p;
				unset($params[$key]);

				$param = arr::get($this->_map, $last_param);
				if ($param AND isset($this->{$param}))
					$this->{$param} = TRUE;
			} else if ($last_param) {
				unset($params[$key]);

				$param = arr::get($this->_map, $last_param);
				if ($param AND isset($this->{$param}))
					$this->{$param} = $p;

				$last_param = NULL;
			}
		}

		$this->_params = $params;
		return $this;
	}

	public function nparams($params)
	{
		$this->clear();

		$class = new ReflectionClass(get_class($this));

		foreach ($params as $key=>$p)
		{
			if ($class->hasProperty($key))
			{
				$this->{$key} = $p;
			} else {
				$this->_params[] = $p;
			}
		}

		return $this;
	}

	/*
	 * Clear all named and unnamed params
	 *
	 * @return Console
	*/
	public function clear()
	{
		// clear unnamed params
		$this->_params = array();

		// clear named params
		foreach ($this->_map as $key=>$field)
			$this->{$field} = NULL;

		return $this;
	}

	/*
	 * Run the command.
	 *
	 * @return 	string	message to show in console
	*/
	abstract public function run();

	/*
	 * Get help for the command
	 *
	 * @return	string	return help message about command
	*/
	public function help()
	{
		$parts = explode('_', get_class($this));
		$command = utf8::strtolower(array_pop($parts));

		$help_file = 'console/command/'.$command;

		if ($help_file = Kohana::find_file('guide', $help_file, 'md'))
			return file_get_contents($help_file);
		else
			return __("No help for the command :command", array(':command'=>$command));
	}

	/*
	 * Get params as associative array
	 *
	 * @return Array array of the params
	*/
	public function as_array()
	{
		$result = array();
		foreach ($this->_map as $key=>$field)
			$result[$field] = $this->{$field};

		return $result;
	}

	/*
	 * Check input params.
	 * throw Validate Exception if something wrong
	 *
	 * @return Validate
	*/
	public function check()
	{
		$validate = $this->validator();

		if ( ! $validate->check())
		{
			throw new Validate_Exception($validate);
		}

		return $validate;
	}

	/*
	 * Create and return object to validate command
	 *
	 * @return Validate
	*/
	public function validator()
	{
		// create validator
		$validate = Validate::factory($this->as_array());

		// add rules
		$this->add_rules($validate);

		return $validate;
	}

	/*
	 * This function must be overload in commands, for check input params
	*/
	public function add_rules(Validate $validate)
	{
		// in abstract class do nothing
	}

	/*
	 * Check if module exist
	 *
	 * @return boolean;
	*/
	public static function check_module($module)
	{
		// get modules
		$modules = Kohana::modules();

		// each module must be configured with path
		return ! empty($modules[$module]);
	}

	/*
	 * Check for existing database configuration
	 *
	 * @return boolean
	*/
	public static function check_dbgroup($db_group)
	{
		// load group
		$group = Kohana::config('database.'.$db_group);

		// for empty group return false
		return is_array($group) AND ! empty($group);
	}

	/*
	 * Check if table in database exist
	 *
	 * @return boolean
	*/
	public static function check_table($table, $group)
	{
		$db = Database::instance($group);

		$tables = $db->list_tables();

		return in_array($table, $tables);
	}
}