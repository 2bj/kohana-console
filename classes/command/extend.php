<?php defined('SYSPATH') OR die('No direct access allowed.');

class Command_Extend extends Command {

	public function run($params)
	{
		$class = strtolower(array_shift($params));
		$parts = explode('_', $class);
		$class_file = array_pop($parts);
		$class_dir = implode(DIRECTORY_SEPARATOR, $parts);
		
		$cur_dir = $parts ? array_shift($parts) : '';
		$directory = arr::get($params, '-d', 'kohana');
		if ($cur_dir == $directory)
			return 'Class already extended.';
		
		$file = Kohana::find_file('classes', $class_dir.'/'.$class_file);
		
		if ( ! $file)
			return 'Class not found';
		
		$text = file_get_contents($file);
		$reg = "#class\s+($class).*?{\s*}#i";
		
		if ( ! preg_match($reg, $text))
		{
			$new_dir = preg_replace('#(classes).*#', '$1', $file).DIRECTORY_SEPARATOR.$directory;
			$new_class = $directory.'_'.$class;
			
			$text = preg_replace("#(class\s+)($class)#i", '$1'.$new_class, $text);
			
			if ( ! is_dir($new_dir))
				mkdir ($new_dir);
			file_put_contents($new_dir.DIRECTORY_SEPARATOR.$class_file.EXT, $text);
			
			file_put_contents($file, <<<EOD
<?php defined('SYSPATH') OR die('No direct access allowed.');

class $class extends $new_class {};	
EOD
);
			return 'Class extended.';
		}
		
		
		return 'Class already extended.';
	}

	public function get_help()
	{
		return <<<EOD
extend class:
usage: extend <class> [-d <directory>]

EOD;
	}
}