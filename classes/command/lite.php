<?php defined('SYSPATH') or die('No direct script access.');

class Command_Lite extends Command {
	
	protected $_errors = array();
	protected $_load_classes = array();
	
	public function run($options)
	{
		if (isset($options['-r']))
		{
			if (file_exists(DOCROOT.'index_old.php'))
			{
				@copy(DOCROOT.'index_old.php', DOCROOT.'index.php');
				@unlink(DOCROOT.'index_old.php');
				
				return 'index.php has been reverted';
			}
			
			return 'Noting revert';
		} else {
			$this->_errors = $this->_load_classes = array();
			$classes = array(
				'arr',
				'controller_template',
				'form',
				'html',
				'inflector',
				'model',
				'profiler',
				'request',
				'route',
				'utf8',
				'view',
				'config',
				'db',
				'database',
				'kohana_log_file',
			);
			
			$content = file_get_contents(DOCROOT.'index.php').'?>';
			
			foreach ($classes as $class)
				$content .= $this->get_class($class);
			
			if ( ! file_exists(DOCROOT.'index_old.php'))
				@copy(DOCROOT.'index.php', DOCROOT.'index_old.php');
			
			file_put_contents (DOCROOT.'index.php', $content);
			
			return 'success';
		}
	}
	
	protected function get_class($class)
	{
		$class = utf8::strtolower($class);
		if (isset($this->_load_classes[$class]))
			return '';
		$this->_load_classes[$class] = $class;
	
		$file = Kohana::find_file('classes', str_replace('_', '/', $class));
		
		if (empty($file))
		{
			$this->_errors[] = $class;
			return '';
		}
		
		$text = file_get_contents($file);
		$content = '';
		
		if (preg_match ('#class[\s]+'.$class.'[\s]+extends[\s]+([a-z0-9_-]+)#i', $text, $matches))
			$content = $this->get_class($matches[1]);
		
		$content .= $text.'?>';
		
		return $content;
	}
	
	public function help()
	{
		return <<<EOD
Increase performance by compose often used classes in one file and replace index.php. Save old index.php as index_old.php. This command can be reverted using -r

usage:
lite [-r]

options:
-r - revert old index.php file

EOD;
	}
}