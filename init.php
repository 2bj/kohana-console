<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

// Static file serving (CSS, JS, images)
Route::set('console/media', 'console/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'console',
		'action'     => 'media',
		'file'       => NULL,
	));

// for generated files
Route::set('console/file', 'console/file/<command>/<file>', array('command'=>'[a-zA-Z0-9]+', 'file' => '[a-zA-Z0-9]+'))
	->defaults(array(
		'controller' => 'console',
		'action'     => 'file',
		'file'       => NULL,
	));

// User guide pages, in modules
Route::set('console', 'console(/<command>)')
	->defaults(array(
		'controller' => 'console',
		'action'     => 'index',
		'command'    => '',
	));