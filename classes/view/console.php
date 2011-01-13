<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class View_Console extends View {

	/*
	 * @var Current theme
	*/
	public $theme = 'default';

	/**
	 * Returns a new View_Console object. If you do not define the "file" parameter,
	 * you must call [View_Console::set_filename].
	 *
	 *     $view = View_Console::factory($file);
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  View_Console
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new View_Console($file, $data);
	}

	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @param   string  view filename
	 * @return  View
	 * @throws  Kohana_View_Exception
	 */
	public function set_filename($file)
	{
		$default = 'console/themes/default/'.$file;
		$file = 'console/themes/'.Kohana::config('console.theme').'/'.$file;
		if ((($path = Kohana::find_file('views', $file)) === FALSE) AND (($path = Kohana::find_file('view', $default)) == FALSE))
		{
			throw new Kohana_View_Exception('The requested view :file could not be found', array(
				':file' => $file,
			));
		}

		// Store the file path locally
		$this->_file = $path;

		return $this;
	}
} // End View