<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class KohanaConsoleTest extends PHPUnit_Framework_TestCase {

	/*
	 * Init enronment and resources
	*/
	public function setUp()
	{
		$this->runSchema('setup.sql');
	}

	/*
	 * Deinit environment
	*/
	public function teardown()
	{
		$this->runSchema('teardown.sql');
	}

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
	 * Run sql script
	 * @param string file name of sql script
	*/
	public function runSchema($schema)
	{
		$testDb = Kohana::config('database.test');

		if(is_null($testDb))
			return false;
		$command = "-u{$testDb['connection']['username']} -p{$testDb['connection']['password']} {$testDb['connection']['database']}";
		$filePath = APPPATH . 'tests/data/' . $schema;
		exec("mysql $command < $filePath ");
	}
}