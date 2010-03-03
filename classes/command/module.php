<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Module extends Command {

	public $module = '';

	public function run()
	{
		$this->module OR $this->module = array_shift($this->_params);

		$directory = MODPATH.$module;

		if (is_dir($directory))
			return __('Error: module exists');
		else {
			mkdir($directory.DIRECTORY_SEPARATOR.'classes', 644, TRUE);
			return __("Create module :module in directory - :directory", array(':module'=>$module, ':directory'=>$directory));
		}
	}
}