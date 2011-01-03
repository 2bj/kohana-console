<?php defined('SYSPATH') or die('No direct script access.');

class Command_Lite extends Command {
	
	protected $_map = array(
		'-r' => 'revert',
	);
	
	protected $_errors = array();
	protected $_load_classes = array();

	public $revert = FALSE;
	
	public $prefix = '_old';

	public function run()
	{
		$index = DOCROOT.'index.php';
		$index_old = DOCROOT.'index'.$this->prefix.'.php';
		
		if ($this->revert)
		{
			if (file_exists($index_old))
			{
				@copy($index_old, $index);
				@unlink($index_old);

				return __('index.php has been reverted');
			}

			return __('Noting revert');
		} else {
			$this->_errors = $this->_load_classes = array();
			$classes = array(
				'arr',
				'controller_template',
				'form',
				'html',
				'url',
				'inflector',
				'model',
				'profiler',
				'request',
				'route',
				'utf8',
				'view',
				'i18n',
				'kohana_log',
				'kohana_config',
				'kohana_config_file',
				'kohana_config_reader',
				'db',
				'database',
				'kohana_log_file',
			);
			
			if (file_exists($index_old))
				$content = file_get_contents($index_old).'?>';
			else
				$content = file_get_contents($index).'?>';
			
			foreach ($classes as $class)
				$content .= $this->get_class($class);
			
			if ( ! file_exists($index_old))
				@copy($index, $index_old);
			
			file_put_contents ($index, $content);


			$result = array();
			$result[] = __("Success");

			if ( ! empty($this->_errors))
			{
				$result[] = __("Classes that do not find:");
				foreach ($this->_errors as $class)
					$result[] = $class;
			}

			return $result;
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
}