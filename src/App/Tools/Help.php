<?php
namespace Clyde\Tools;

use Clyde\Objects\Application_Object;
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

		$this->Table = new Table;
		$rows        = [];
		foreach ($Application_Object->commands as $command) {
			$rows[] = [$command->command_name, $command->about];
		}

		$help_info = $this->Table->buildTable([
			'headers' => ['Command', 'Description'],
			'rows' => $rows
		]);

		$file = preg_replace('%\#commands\#%', $help_info, $file);

		return $file;
	}
}