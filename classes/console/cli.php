<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @author		FerumFlex <ferumflex@gmail.com>
 */

class Console_Cli extends Console {

	public $prompt = NULL;

	public $line_return = NULL;

	/**
	 * Read and return the line from input
	 *
	 * @return  string	input string
	 */
	public function readline()
	{
		$input = '';
		if (extension_loaded('readline'))
		{
			$input = readline($this->prompt);
			readline_add_history($input);
		} else {
			echo $this->prompt;
			$input = fgets(STDIN);
		}

		return trim($input);
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

	public function save_file($dir, $file, $text)
	{
		$file = str_replace('_', DIRECTORY_SEPARATOR, $file);

		$dest = $dir.DIRECTORY_SEPARATOR.$file;
		if (is_file($dest) AND $this->dialog(__('File :file exists. Overwrite? (yes|no)', array(':file'=>$dest)), array('yes', 'no')) != 'yes')
			return __('Nothing created');

		if ( ! is_dir(dirname($dest)))
			mkdir(dirname($dest), 644, TRUE);

		file_put_contents ($dest, $text);

		return __('Create file :file', array(':file'=>$file));
	}

	public function print_line($lines, $return = TRUE)
	{
		if ( ! is_array($lines))
			$lines = array($lines);

		foreach ($lines as $text)
		{
			// echo text
			echo $text;

			// print line return if nessecary
			if ($return)
				echo $this->line_return;
		}

		return $this;
	}
}