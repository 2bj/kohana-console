<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?="<?php defined('SYSPATH') OR die('No direct access allowed.');";?> 

return array(
<?php foreach ($model->fields() as $name=>$field):?>
	'<?=$name?>' => array(
<?php foreach ($field->rules as $rule=>$params):?>
<?php
	$field_name = empty($field->label) ? $name : $field->label;
	$values = array(':field' => $field_name);

	if ($params)
	{
		foreach ($params as $key => $value)
		{
			// Add each parameter as a numbered value, starting from 1
			$values[':param'.($key + 1)] = $value;
		}
	}
?>
		'<?=$rule?>' => '<?=strtr(Kohana::message('validate', $rule), $values)?>',
<?php endforeach ?>
<?php foreach ($field->callbacks as $callback):?>
<?php
	$rule = $callback;
	if (is_string($callback) AND strpos($callback, '::') !== FALSE)
		$rule = array_pop(explode('::', $callback, 2));
	elseif (is_array($callback))
		$rule = array_pop($callback);
?>
		'<?=$rule?>' => '',
<?php endforeach ?>
	),
<?php endforeach ?>
);