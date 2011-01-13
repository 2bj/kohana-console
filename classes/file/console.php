<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class File_Console {

	protected $_filename = NULL;

	protected $_data = NULL;

	public function __construct($filename, $data)
	{
		$this->_filename = $filename;
		$this->_data = $data;
	}

	public function status()
	{
		$status = 'new';

		if (file_exists($this->_filename))
			$status = 'exsits';

		return $status;
	}

	public function save()
	{
		file_put_contents($this->_filename, $this->_data);

		return __('Create file :file', array(':file'=>$this->_filename));
	}

	public function filename()
	{
		return $this->_filename;
	}

	public function data()
	{
		return $this->_data;
	}

	/**
	 * Print message and wait until user input string from the choices
	 *
	 * @param	string	message to print on concole
	 * @return  array	array of the correct answers (yes, no, wait, etc)
	 */
	public function dialog($message, $choices)
	{
		echo $message;

		$res = '';
		while (TRUE)
		{
			$res = $this->readline();
			if (in_array($res, $choices))
				break;

			echo __('Type').implode(',', $choices).$this->line_return;
		}

		return $res;
	}
}