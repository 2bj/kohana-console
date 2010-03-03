<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Error extends Command {

	protected $_map = array(
		'-f' => 'file',
		'-m' => 'module',
	);

	public $model = '';

	public $file = '';

	public $module = '';

	public function run()
	{
		$this->model OR $this->model = array_shift($this->_params);

		if (empty($this->model))
			return $this->help();

		$this->file OR $this->file = $model.'_error';

		if ($this->module)
		{
			$modules = Kohana::modules();
			$dir = MODPATH.$modules[$module].DIRECTORY_SEPARATOR;
		} else
			$dir = APPPATH;
		$dir = $dir.'messages';

		$class = Sprig::factory($this->model);

		$data = array(
			'model' => $class,
		);

		$error_text = View::factory('console/error', $data)->render();

		return $this->console->save_file($dir, $this->file, $error_text);
	}
}