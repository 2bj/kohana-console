<?php defined('SYSPATH') or die('No direct script access.');
<?="<?php defined('SYSPATH') or die('No direct script access.');"?>

class Controller_Admin_Content extends Controller_Admin {
	public function action_index()
	{
		access::check_permission('module_content', 'view');
		
		$query = array(
			'phrase'		=> arr::get($_GET, 'phrase', ''),
			'visible'		=> arr::get($_GET, 'visible', 0),
		);
		
		$db = DB::select();
		
		$content = Sprig::factory('content');
		$count = $content->count(clone $db);
		$pagination = new Pagination(array(
			'items_per_page'	=> arr::get($_GET, 'per_page', 10),
			'total_items'		=> $count,
			'view'				=> Kohana::config('cms.theme').'/pager',
			'current_page'		=> array('source' => 'query_string', 'key' => 'page'),
		));
		
		$contents = $content->load($db->offset($pagination->offset), $pagination->items_per_page);
		
		$data = array(
			'contents'		=> $contents,
			'query'			=> $query,
		);
		
		$this->template->content = Theme_View::factory('content/list_pages', $data);
		$this->template->pagination = $pagination;
		$this->template->title = 'Все страницы';
	}
	
	public function action_edit($id = NULL)
	{
		access::check_permission('module_content', 'add_edit');
		
		$content = Sprig::factory('content');
		if ($id)
		{
			$content->load(DB::select()->where('id', '=', $id));
			
			if ( ! $content->loaded())
				throw new Exception404;
			
			$this->template->title = 'Редактировать страницу';
		} else {
			$this->template->title = 'Добавить страницу';
		}
		
		$form = $content->as_array();
		$errors = array();

		if ($_POST)
		{
			$content->values($_POST);
			
			try
			{
				if ($content->loaded())
					$content->update();
				else
					$content->create();
				
				myurl::redirect('admin/content');
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('validation');
				$form = array_merge($form, $content->as_array());
			}
		}
		
		$data = array(
			'form'      => $form,
			'errors'    => $errors,
			'content'	=> $content,
		);
		
		$this->template->content = Theme_View::factory('content/add_edit_content', $data);
		$this->tpl->add_crumblink('Все страницы', 'admin/content');
	}

	public function action_action()
	{
		$contents_ids = arr::get($_POST, 'contents_ids', array());
		$action = arr::get($_POST, 'action', 'default');
		
		foreach ($contents_ids as $content_id)
		{
			$content = Sprig::factory('content');
			$content->load(DB::select()->where('id', '=', $content_id));
			
			if ( ! $content->loaded())
				continue;
			
			switch ($action)
			{
				case 'delete':
					access::check_permission('module_content', 'delete');
					
					$title = $content->title;
					$content->delete();
					
 					flash::set('info', 'Страница удалена - '.$title);	
					break;
				case 'publish':
					access::check_permission('module_content', 'add_edit');
					
					$content->published = TRUE;
					$content->update();
					
 					flash::set('info', 'Страница опубликована - '.$content->title);	
					break;
				case 'unpublish':
					access::check_permission('module_content', 'add_edit');
					
					$content->published = FALSE;
					$content->update();
					
 					flash::set('info', 'Страница скрыта - '.$content->title);	
					break;
			}
		}
		
		Request::instance()->redirect(Request::$referrer);
	}
}