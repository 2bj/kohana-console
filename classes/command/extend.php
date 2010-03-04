<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Extend extends Command {

	public $_map = array(
		'-d' => 'directory',
		'-r' => 'revert',
	);

	public $file = '';

	public $directory = 'kohana';
	
	public $revert = FALSE;

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
		
		if ($this->revert)
		{
			$reg = "#class\s+($class)\s+extends\s+([a-z0-9-_]+).*?{\s*}#i";
			if (preg_match($reg, $text, $matches)) {
				$new_class = $matches[2];
				$new_file = str_replace('_', '/', $new_class);
				
				$new = Kohana::find_file('classes', $new_file);
				
				if ( ! $new)
					return __('Class not found');
				
				$new_text = file_get_contents($new);
				
				$new_text = preg_replace("#(class\s+)($new_class)#i", '$1'.$class, $new_text);
				file_put_contents($file, $new_text);
				@unlink($new);
				
				return __('Class unextended');
			}
			
			return __('Class not extended.');
		} else {
			$reg = "#class\s+($class).*?{\s*}#i";
			if ( ! preg_match($reg, $text)) {
				$s = preg_quote(DIRECTORY_SEPARATOR);
				$directory = dirname(preg_replace('#(.*?'.$s.')classes('.$s.'.*)#', '$1classes'.$s.$this->directory.'$2', $file));
				$new_class = str_replace('/', '_', $this->directory).'_'.$class;

				$text = preg_replace("#(class\s+)($class)#i", '$1'.$new_class, $text);

				if ( ! is_dir($directory))
					mkdir ($directory);
				file_put_contents($directory.DIRECTORY_SEPARATOR.basename($file), $text);

				file_put_contents($file, View::factory('console/extend', array('class'=>$class, 'new_class'=>$new_class)));
				return __('Class extended');
			}
		}
		
		return __('Class already extended');
	}
}