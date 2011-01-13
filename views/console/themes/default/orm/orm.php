<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?="<?php defined('SYSPATH') OR die('No direct access allowed.');";?>


class Model_<?=utf8::ucfirst(inflector::singular($class))?> extends ORM {

	protected $_db = '<?=$group?>';

	protected $_table_name = '<?=$table?>';

<?php if (isset($primary_key)):?>
	protected $_primary_key = '<?=$primary_key?>';
<?php endif ?>

	protected $_belongs_to = array(
<?php foreach ($belongs_to as $column=>$info):?>
		'<?=$column?>' => array(
			'model' => '<?=$info['params']['model']?>',
			'foreign_key' => '<?=$info['params']['column']?>',
		),
<?php endforeach ?>
	);

	protected $_has_many = array();

	protected $_has_one = array();

}