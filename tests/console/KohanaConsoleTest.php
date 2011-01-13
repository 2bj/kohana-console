<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class KohanaConsoleTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests the cache static instance method
	 */
	public function testInstance()
	{
		$cli_instance  = Console::instance('cli');
		$cli_instance2 = Console::instance('cli');

		// Try and load a Cache instance
		$this->assertType('Console', Console::instance());
		$this->assertType('Console_Cli', $cli_instance);

		// Test instances are only initialised once
		$this->assertTrue(spl_object_hash($cli_instance) == spl_object_hash($cli_instance2));

		// Test the publically accessible Cache instance store
		$this->assertTrue(spl_object_hash(Console::$instances['cli']) == spl_object_hash($cli_instance));
	}

	/*
	 *
	*/
	public function testModel()
	{
		$console = Console::instance();
		$console->exec('orm users -d sprig -g test');

		$model = Sprig::factory('user');
		$this->assertObject($model);
	}
}