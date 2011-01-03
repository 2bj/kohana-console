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
	);

	/*
	 * @var string name model for which generate
	*/
	public $model = '';

	/*
	 * @var string filename
	*/
	public $file = '';

	/*
	 * @var string module name
	*/
	public $module = '';

	/*
	 * @return string
	*/
	public function run()
	{
		// get model prom params
		$this->model OR $this->model = array_shift($this->_params);

		// model can not be empty
		if (empty($this->model))
			return $this->help();

		// name of file
		$this->file OR $this->file = $this->model.'_error';

		// define name of file and directory
		if ($this->module)
		{
			$modules = Kohana::modules();
			$dir = $modules[$module].DIRECTORY_SEPARATOR;
		} else
			$dir = APPPATH;
		$dir = $dir.'messages';

		// create new model
		$class = Sprig::factory($this->model);

		$data = array(
			'model' => $class,
		);

		$error_text = View::factory('console/error', $data)->render();

		return $this->console->save_file($dir, $this->file.EXT, $error_text);
	}
}