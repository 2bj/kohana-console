<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Extend extends Command {

	public $_map = array(
		'-d' => 'directory',
	);

	public $file = '';

	public $directory = 'kohana';

	public function run()
	{
		$this->file OR $this->file = strtolower(array_shift($this->_params));

		if (empty($this->file))
			return $this->help();

		$file = Kohana::find_file('classes', $this->file);

		if ( ! $file)
			return __('Class not found');

		$class = str_replace('/', '_', $this->file);
		$text = file_get_contents($file);
		$reg = "#class\s+($class).*?{\s*}#i";

		if ( ! preg_match($reg, $text))
		{
			$new_dir = dirname($file).DIRECTORY_SEPARATOR.$this->directory;
			$new_class = str_replace('/', '_', $this->directory).'_'.$class;

			$text = preg_replace("#(class\s+)($class)#i", '$1'.$new_class, $text);

			if ( ! is_dir($new_dir))
				mkdir ($new_dir);
			file_put_contents($new_dir.DIRECTORY_SEPARATOR.basename($file), $text);

			file_put_contents($file, View::factory('console/extend', array('class'=>$class, 'new_class'=>$new_class)));
			return __('Class extended.');
		}

		return __('Class already extended.');
	}
}