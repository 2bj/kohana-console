<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package		AltConstructor
 * @author		Anton <anton@altsolution.net>
 */
class Column_Mysql {
	
	/**
	 * Analyze table and guess type of column (Auto, Email, Enum etc)
	 * 
	 * @table	string	table in the database
	 * @group	string	group for connection to database
	 * @return  array	with some information than Database->list_columns()
	 */
	function get_columns($table, $group = 'default')
	{
		$db = Database::instance($group);
		
		$columns = array();
		$foreigns = $this->get_foreigns($table, $group);
		$uniques = $this->get_uniques($table, $group);
		
		foreach ($db->list_columns($table) as $name=>$data)
		{
			$params = array();
			$type = '';
			$params['empty'] = $data['is_nullable'];
			$params['description'] = $data['comment'];
			$params['label'] = '';
			$params['default'] = (string)$data['column_default'];
			
			// is unique key
			foreach ($uniques as $keys)
			{
				if ((count($keys) == 1) AND (in_array($name, $keys)))
				{
					$params['unique'] = TRUE;
					break;
				}
			}
			
			switch ($data['type'])
			{
				case 'int':
					if ($data['extra'] == 'auto_increment') {
						$type = 'auto';
						$params = array();
					} else if (arr::get($data, 'length', 0) == 1)
						$type = 'boolean';
					else if (isset($foreigns[$name])) {
						$type = 'belongsto';
						$params['model'] = inflector::singular($foreigns[$name]['table']);
						$params['column'] = $name;
						$params['null'] = $params['empty'];
						unset($params['empty']);
						$name = $params['model'];
					} else if ( ! empty($data['choices'])) {
						$type = 'enum';
						$params['choices'] = $data['choices'];
					} else if (preg_match('#(created|time|updated)#', $name)) {
						$type = 'timestamp';
						$params['default'] = array('time()');
					} else
						$type = 'integer';
					
				break;
				
				case 'double':
				case 'float':
					$type = 'float';
				break;
				
				case 'string';
					if (isset($data['character_maximum_length']))
						$params['max_length'] = (int)$data['character_maximum_length'];
					switch ($data['data_type'])
					{
						case 'text':
							$type = 'text';
						break;
						default:
							if (preg_match('#(email)#', $name))
								$type = 'email';
							elseif (preg_match('#(password)#', $name))
								$type = 'password';
							else
								$type = 'char';
						break;
					}
				
				break;
			}
			
			$columns[$name] = array(
				'type' => $type,
				'params' => $params,
				'db' => $data,
			);
		}
		
		return $columns;
	}

	/**
	 * Get Foreigns keys for the table
	 *
	 * @table	string	table in the database
	 * @group	string	group for connection to database
	 * @return  array	foreigns keys, for example:
	 * array(
	 * 		'user_id'=>
	 * 			array(
	 * 				'table'=>'users',
	 * 				'column'=>'id'
	 * 			)
	 * 		);
	 */
	protected function get_foreigns($table, $group = 'default')
	{
		$row = Database::instance($group)->query(Database::SELECT, 'SHOW CREATE TABLE '.$table, FALSE);
		
		$matches = array();
		$regexp = '#FOREIGN KEY\s+\(([^\)]+)\)\s+REFERENCES\s+([^\(^\s]+)\s*\(([^\)]+)\)#mi';
		
		foreach ($row as $sql)
		{
			if (preg_match_all($regexp, $sql['Create Table'], $matches, PREG_SET_ORDER))
				break;
		}
		
		$foreigns = array();
		foreach($matches as $match)
		{
			$keys = array_map('trim', explode(',', str_replace('`', '', $match[1])));
			$fks = array_map('trim', explode(',', str_replace('`', '', $match[3])));
			foreach($keys as $k=>$name)
			{
				$foreigns[$name] = array(
					'table' => str_replace('`', '', $match[2]),
					'column' => $fks[$k],
				);
			}
		}
		
		return $foreigns;
	}
	
	/**
	 * Get Unique keys for the table
	 *
	 * @table	string	table in the database
	 * @group	string	group for connection to database
	 * @return  array	unique keys
	 */
	protected function get_uniques($table, $group = 'default')
	{
		$row = Database::instance($group)->query(Database::SELECT, 'SHOW INDEX FROM '.$table, FALSE);
		
		$uniques = array();
		foreach ($row as $r)
		{
			if ($r['Key_name'] == 'PRIMARY')
				continue;
			
			if ( ! $r['Non_unique'])
			{
				$keys = array_map('trim', explode(',', str_replace('`', '', $r['Column_name'])));
				$uniques[] = $keys;
			}
		}
		
		return $uniques;
	}
}