<?php defined('SYSPATH') or die('No direct script access.');
<?="<?php defined('SYSPATH') or die('No direct script access.');"?>

class <?=$controller?> extends <?=$parent?> {
	public function action_index()
	{
		$db = DB::select();
		
		$<?=$model?> = Sprig::factory('<?=$content?>');
		$count = $<?=$model?>->count(clone $db);
		$pagination = new Pagination(array(
			'items_per_page'	=> arr::get($_GET, 'per_page', 10),
			'total_items'		=> $count,
			'view'				=> Kohana::config('cms.theme').'/pager',
			'current_page'		=> array('source' => 'query_string', 'key' => 'page'),
		));
		
		$<?=$models?> = $<?=$model?>->load($db->offset($pagination->offset), $pagination->items_per_page);
		
		$data = array(
			'<?=$model?>'	=> $<?=$models?>,
			'query'			=> $query,
		);
		
		$this->template->content = Theme_View::factory('<?=$view_folder?>/<?=$view_list?>', $data);
		$this->template->pagination = $pagination;
		$this->template->title = 'Все объекты';
	}
	
	public function action_edit($id = NULL)
	{
		$<?=$model?> = Sprig::factory('<?=$content?>');
		if ($id)
		{
			$<?=$model?>->load(DB::select()->where('id', '=', $id));
			
			if ( ! $<?=$model?>->loaded())
				throw new Exception404;
			
			$this->template->title = 'Редактировать объект';
		} else {
			$this->template->title = 'Добавить объект';
		}
		
		$form = $<?=$model?>->as_array();
		$errors = array();

		if ($_POST)
		{
			$<?=$model?>->values($_POST);
			
			try
			{
				if ($<?=$model?>->loaded())
					$<?=$model?>->update();
				else
					$<?=$model?>->create();
				
				myurl::redirect(<?=$controller_folder?>);
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('validation');
				$form = array_merge($form, $<?=$model?>->as_array());
			}
		}
		
		$data = array(
			'form'      => $form,
			'errors'    => $errors,
			'content'	=> $<?=$model?>,
		);
		
		$this->template->content = Theme_View::factory('<?=$view_folder?>/<?=$view_add_edit?>', $data);
		$this->tpl->add_crumblink('Все объекты', <?=$controller_folder?>);
	}

	public function action_action()
	{
		$<?=$model?>_ids = arr::get($_POST, '<?=$model?>_ids', array());
		$action = arr::get($_POST, 'action', 'default');
		
		foreach ($<?=$model?>_ids as $<?=$model?>_id)
		{
			$<?=$model?> = Sprig::factory('<?=$model?>');
			$<?=$model?>->load(DB::select()->where('id', '=', $<?=$model?>_id));
			
			if ( ! $<?=$model?>->loaded())
				continue;
			
			switch ($action)
			{
				case 'delete':
					$title = $<?=$model?>->$<?=$object->tk()?>;
					$<?=$model?>->delete();
					
					flash::set('info', 'Объект удален - '.$title);	
					break;
			}
		}
		
		Request::instance()->redirect(Request::$referrer);
	}
}