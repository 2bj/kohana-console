<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?="<?php defined('SYSPATH') OR die('No direct access allowed.');?>";?>

class Model_<?=utf8::ucfirst(inflector::singular($table))?> extends Sprig {
	
	public function _init()
	{
		$this->_fields += array(
			<?php foreach ($columns as $name=>$data):?>
			<?php
				$params = array();
				$type = '';
			?>
			<?php
				$params['empty'] = $data['is_nullable'];
				$params['description'] = $data['comment'];
				
				switch ($data['type'])
				{
					case 'int':
						if ($data['extra'] == 'auto_increment')
							$type = 'Auto';
						else if ($data['length'] == 1)
							$type = 'Boolean';
						else
							$type = 'Integer';
						
					break;
					
					case 'varchar';
						$params['max_length'] = $data['character_maximum_length'];
						switch ($data['data_type'])
						{
							case 'text':
								$type = 'Text';
							break;
							default:
								if (strpos($name, 'email') !== FALSE)
									$type = 'Email';
								else
									$type = 'Char';
							break;
						}
					
					break;
				}
			?>
				'<?=$name?>' => 
			<?php endforeach ?>
		);
	}
}