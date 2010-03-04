<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?="<?php defined('SYSPATH') OR die('No direct access allowed.');";?> 

class Model_<?=utf8::ucfirst(inflector::singular($table))?> extends Sprig {

	protected $_db = '<?=$group?>';

	protected $_table = '<?=$table?>';

	public function _init()
	{
		$this->_fields += array(
<?php foreach ($columns as $name=>$info):?>
			'<?=$name?>' => new Sprig_Field_<?=$info['type']?>(array(
<?php foreach ($info['params'] as $key=>$value):?>
<?php if (is_string($value)):?>
				'<?=$key?>' => '<?=$value?>',
<?php elseif (is_bool($value)):?>
				'<?=$key?>' => <?=$value ? 'TRUE' : 'FALSE'?>,
<?php else:?>
				'<?=$key?>' => <?=$value?>,
<?php endif ?>
<?php endforeach ?>
			)),
<?php endforeach ?>
		);
	}
}