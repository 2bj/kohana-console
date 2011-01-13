<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

return array(
	'group' => array(
		'not_empty' => 'Database group must be filled',
		'Command::check_dbgroup' => 'Database group does not exist in config',
	),
	'module' => array(
		'Command::check_module' => 'Module does not exist',
	),
	'driver' => array(
		'in_array' => 'Driver must be of the following: orm, sprig, hive, jelly',
	),
	'table' => array(
		'not_empty' => 'Table must be filled',
		'Command::check_table' => 'Table does not exist in database',
	),
);