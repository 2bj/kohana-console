<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package		AltConstructor
 * @author		Anton <anton@altsolution.net>
 */
class Command_Crud extends Command {
	
	protected $_map = array(
		'-f' => 'folder',
	);
	
	public $model = '';
	
	public $folder = '';
	
	public $module = APPPATH;
	
	public function run()
	{
		$this->model OR $this->model = array_pop($this->_params);
		if ($this->module)
		{
			$modules = Kohana::modules();
			$directory = MODPATH.$modules[$this->module].DIRECTORY_SEPARATOR;
		}
		
		$object = Sprig::factory($this->model);
		
		$data = array(
			'object' => $object,
		);
		
		$controller_text = View::factory('console/crud/controller', $data)->render();
		$add_edit_text = View::factory('console/crud/add_edit', $data)->render();
		$list_text = View::factory('console/crud/list', $data)->render();
		
		return $this->console->save_file($directory.'classes'.DIRECTORY_SEPARATOR.'controller', $this->model.EXT, $contoller_text) . LINE_RETURN .
			$this->console->save_file($directory.'views'.DIRECTORY_SEPARATOR.($this->folder ? $this->folder . DIRECTORY_SEPARATOR : '').$this->model, 'add_edit_'.$this->model, $add_edit_text) . LINE_RETURN .
			$this->console->save_file($directory.'views'.DIRECTORY_SEPARATOR.($this->folder ? $this->folder . DIRECTORY_SEPARATOR : '').$this->model, 'list_'.$this->model, $list_text);
	}
}