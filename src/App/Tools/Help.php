<?php
namespace Clyde\Tools;

use Clyde\Objects\Application_Object;
use Exception;

class Help
{
	const TEMPLATES = __DIR__ . '/../Templates';

	protected array $help_lexemes = [
		'%\#application_name\#%',
		'%\#author\#%',
		'%\#version\#%',
		'%\#website\#%',
		'%\#about\#%',
	];

	public function buildHelpOutPut(Application_Object $Application_Object) {
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