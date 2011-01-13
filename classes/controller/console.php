<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Console extends Controller_Template {

	public $template = 'console/layout/template';

	public function before()
	{
		parent::before();

		// for security: not work in production
		if (Kohana::$environment == Kohana::PRODUCTION)
			die('Production enabled');
	}

	public function action_media()
	{
		$this->auto_render = FALSE;

		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media/console', $file, $ext))
		{
			// Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
			$this->request->check_cache(sha1($this->request->uri).filemtime($file));

			// Send the file content as the response
			$this->request->response = file_get_contents($file);
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the proper headers to allow caching
		$this->request->headers['Content-Type']   = File::mime_by_ext($ext);
		$this->request->headers['Content-Length'] = filesize($file);
		$this->request->headers['Last-Modified']  = date('r', filemtime($file));
	}

	public function action_index()
	{
		if (Request::$protocol == 'cli')
			return $this->cli();
		else
			return $this->web();
	}

	public function cli()
	{
		$this->auto_render = FALSE;

		ob_end_clean();

		// first of all print all commands
		$console = Console::instance();
		$this->print_line($console->command('help')->help());

		while (TRUE)
		{
			// read command
			$input = trim($this->readline());

			// empty command? next please
			if (empty($input))
				continue;

			if ($input == 'exit')
				break;

			try
			{
				// exec and print command result
				$result = $console->exec($input);
				$this->print_line($result);

				// if any files exists, save all of them
				foreach ($console->files() as $hash=>$file)
					$this->print_line($file->save());
			} catch (Validate_Exception $e) {
				$errors = $e->array->errors('console');
				$this->print_line($errors);
			} catch (Exception $e) {
				$error_text = __("Some error occured: :message in file: :file line :line", array(':message'=>$e->getMessage(), ':file'=>$e->getFile(), ':line'=>$e->getLine()));
				$this->print_line($error_text);
			}
		}
	}

	public function web()
	{
		$console = Console::instance();
		$command_name = $this->request->param('command');

		// check if command are supported
		$commands = array('error', 'orm');
		if ( ! in_array($command_name, $commands))
			return;

		if ($command_name)
		{
			$command = $console->command($command_name);
			$errors = array();

			if ($_POST)
			{
				try {
					$command->nparams($_POST)->run();

					$answers = arr::get($_POST, 'answers');
					if (arr::get($_POST, 'generate'))
					{
						$files = $console->files();

						foreach ($answers as $hash=>$value)
						{
							if (empty($value))
								continue;

							if ( ! isset($files[$hash]))
								continue;

							$files[$hash]->save();
						}

						$this->request->redirect(Route::get('console')->uri(array('command'=>$command_name)));
					}
				} catch (Validate_Exception $e) {
					$errors = $e->array->errors('console');
				}
			}

			$data = array(
				'console' => $console,
				'command' => $command,
				'command_name' => $command_name,
				'form' => $command->validator(),
				'errors' => $errors,
			);

			$content = View::factory('console/layout/command/'.$command_name, $data);
		} else {
			$content = '';
		}

		$this->template->content = $content;
		$this->template->title = 'Generator for Kohana';
	}

	public function action_file()
	{
		$this->auto_render = FALSE;
		$console = Console::instance();
		$command_name = $this->request->param('command');
		$file = $this->request->param('file');

		$command = $console->command($command_name);

		try {
			$command->nparams($_POST)->run();
		} catch (Validate_Exception $e) {
			// return error
			echo 'Error';
		}

		$files = $console->files();

		if (empty($files[$file]))
			echo 'Error';

		// include required geshi file
		if ( ! class_exists('geshi'))
		{
			require Kohana::find_file('vendors', 'geshi/geshi');
		}

		$geshi = new GeSHi($files[$file]->data(), 'php');

		//
		// And echo the result!
		//
		echo $geshi->parse_code();
	}

	/*
	 * Print line of array of lines
	 *
	 * @var Array or String Array of lines or just one string
	 */
	public function print_line($lines)
	{
		// get line return
 		$line_return = Kohana::config('console.cli.line_return');

		// unify input params
		if ( ! is_array($lines))
			$lines = array($lines);

		foreach ($lines as $text)
		{
			// echo text
			echo $text;
			// echo line return
			echo $line_return;
		}

		return $this;
	}

	/**
	 * Read and return the line from input
	 *
	 * @return  string	input string
	 */
	public function readline()
	{
		// get prompt from config
		$prompt = Kohana::config('console.cli.prompt');

		$input = '';
		if (extension_loaded('readline'))
		{
			$input = readline($prompt);
			readline_add_history($input);
		} else {
			echo $prompt;
			$input = fgets(STDIN);
		}

		return trim($input);
	}
}