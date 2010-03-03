<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Error extends Command {
	
	public function run()
	{
		$model_name = array_shift($this->_params);
		
		if (empty($model_name))
			return $this->help();
		
		$file = arr::get($this->_named, '-f', $model_name.'_error');
		
		$module = arr::get($this->_named, '-m');
		if ($module)
		{
			$modules = Kohana::modules();
			$dir = MODPATH.$modules[$module].DIRECTORY_SEPARATOR;
		} else
			$dir = APPPATH;
		$dir = $dir.'messages';
		
		$model = Sprig::factory($model_name);
		
		$data = array(
			'model' => $model,
		);
		
		$error_text = View::factory('console/error', $data)->render();
		
		return $this->console->save_file($dir, $file, $error_text);
	}
}