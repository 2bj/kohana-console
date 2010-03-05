<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package		AltConstructor
 * @author		Anton <anton@altsolution.net>
 */
class Command_Crud extends Command {
	
	protected $_map = array(
		'-f' => 'folder',
		'-p' => 'parent',
	);
	
	public $model = '';
	
	public $folder = '';
	
	public $module = APPPATH;
	
	public $parent = 'controller_theme';
	
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
			'controller' => utf8::ucfirst('controller_'.($this->folder ? $this->folder.'_' : '').$object),
			'controller_folder' => 'controller'.($this->folder ? '/'.$this->folder : ''),
			'parent' => utf8::ucfirst($this->parent),
			'model' => $this->model,
			'models' => $this->model.'s',
			'view_folder' => ($this->folder ? $this->folder . '/' : '').$this->model,
			'view_add_edit' => 'add_edit_'.$this->model,
			'view_list' => 'list_'.$this->model,
		);
		
		$controller_text = View::factory('console/crud/controller', $data)->render();
		$add_edit_text = View::factory('console/crud/add_edit', $data)->render();
		$list_text = View::factory('console/crud/list', $data)->render();
		
		return $this->console->save_file($directory.'classes'.DIRECTORY_SEPARATOR.'controller', $this->model.EXT, $contoller_text) . LINE_RETURN .
			$this->console->save_file($directory.'views'.DIRECTORY_SEPARATOR.$data['view_folder'], $data['view_add_edit'], $add_edit_text) . LINE_RETURN .
			$this->console->save_file($directory.'views'.DIRECTORY_SEPARATOR.$data['view_folder'], $data['view_list'], $list_text);
	}
}