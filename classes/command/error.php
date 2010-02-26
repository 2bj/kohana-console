<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Error extends Command {
	
	public function run($options)
	{
		$model_name = array_shift($options);
		
		if (isset($options['-f']))
			$file_name = $options['-f'];
		else
			$file_name = $model_name.'_error';
		
		if (isset($options['-m']))
			$dir = MODPATH.$options['-m'].DIRECTORY_SEPARATOR;
		else
			$dir = APPPATH;
		
		$model = Sprig::factory($model_name);
		
		$data = array(
			'model' => $model,
		);
		
		$error_text = View::factory('console/error', $data)->render();
		
		$dir = $dir.'messages';
		if ( ! is_dir($dir))
			mkdir($dir, 644, TRUE);
		
		$file = $dir.DIRECTORY_SEPARATOR.$file_name.EXT;
		
		if (is_file($file) AND Console::dialog('File '.$file.' exists. Overwrite? (yes|no)', array('yes', 'no')) != 'yes')
			return "Nothing created.";
		
		file_put_contents ($file, $error_text);
		
		return "Create error file $file";
	}
	
	public function get_help()
	{
		return <<<EOD
Create error file for sprig model.
Usage: model <model> [-m <module_name>] [-f <file_name>]

-m <module_name> - create file in the input module
-f <file_name> - name of the file. By default model name plus "_error"

EOD;
	}
}