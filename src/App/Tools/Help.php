<?php
namespace Clyde\Tools;

use Clyde\Objects\Application_Object;
use Clyde\Objects\Command_Object;
use Clyde\Tools\Table;
use Exception;

class Help
{
	/**
	 * Templates directory
	 */
	const TEMPLATES = __DIR__ . '/../Templates';

	/**
	 * Table class
	 *
	 * @var Table
	 */
	protected Table $Table;

	/**
	 * template lexemes
	 *
	 * @var array
	 */
	protected array $help_lexemes = [
		'%\#application_name\#%',
		'%\#author\#%',
		'%\#version\#%',
		'%\#website\#%',
		'%\#about\#%',
	];

	/**
	 * Build the help output
	 *
	 * @param Application_Object $Application_Object Application
	 * @return string
	 */
	public function buildHelpOutPut(Application_Object $Application_Object): string {
		$file = $this->parseHelpTemplate($Application_Object);

		$this->Table = new Table;
		$rows        = [];
		foreach ($Application_Object->commands as $command) {
			if ($command->hidden_command === TRUE){
				continue;
			}
			$rows[] = [$command->command_name, $command->about];
		}

		$help_info = $this->Table->buildTable([
			'headers' => ['Command', 'Description'],
			'rows' => $rows
		]);

		$file = preg_replace('%\#commands\#%', $help_info, $file);

		return $file;
	}

	/**
	 * Builds command help screens
	 *
	 * @param Command_Object     $command            The command object
	 * @param Application_Object $Application_Object Application object
	 * @return string
	 */
	public function buildCommandHelpOutput(Command_Object $command, Application_Object $Application_Object): string {
		$file = $this->parseHelpTemplate($Application_Object);
		$rows = [];

		foreach($command->args as $arg) {
			$value  = $arg->is_flag ? '' : '=[VALUE]';
			$rows[] = [
				'--' . $arg->long_name . $value, 
				'-' . $arg->short_name . $value, 
				$arg->help, 
				$arg->required ? 'True' : 'False', 
				$arg->is_flag ? 'True' : 'False',
			];
		}

		$this->Table = new Table;
		$help_info   = <<<TXT
		Command: $command->command_name
		About: $command->about
		
		Usage:


		TXT;
		$help_info  .= $this->Table->buildTable([
			'headers' => ['Arg', 'Alias', 'Description', 'Required', 'Is Flag'],
			'rows' => $rows,
		]);

		$file = preg_replace('%\#commands\#%', $help_info, $file);

		return $file;
	}

	/**
	 * Initial parsing of help template
	 *
	 * @param Application_Object $Application_Object Application Object
	 * @return string
	 */
	protected function parseHelpTemplate(Application_Object $Application_Object): string {
		$help_data = [
			$Application_Object->application_name,
			$Application_Object->author,
			$Application_Object->version,
			$Application_Object->website,
			$Application_Object->about
		];

		$template = self::TEMPLATES . '/help.txt';

		if (isset($Application_Object->template)) {
			$template = $Application_Object->template;
		}

		if (!file_exists($template)) {
			throw new Exception("Help template not found");
		}

		$file_contents = file_get_contents($template);

		$file = preg_replace($this->help_lexemes, $help_data, $file_contents);
		
		return $file;
	}
}