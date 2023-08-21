<?php
namespace Clyde\Core;

use Clyde\Objects\Application_Object;
use Clyde\Request\Request;
use Exception;

/**
 * @phpstan-import-type CommandObject from \Clyde\Objects\Command_Object
 */
class Command_Parser
{
	/**
	 * Builds command data from a request and the Application
	 *
	 * @param Request            $Request            CLI Request
	 * @param Application_Object $Application_Object Application object
	 * @return array{0:CommandObject, 1:array<string, mixed>}
	 */
	public function buildCommandData(Request $Request, Application_Object $Application_Object): array {
		if (empty($Application_Object->commands[$Request->command])) {
			throw new Exception("Command not found " . $Request->command);
		}
		$command = $Application_Object->commands[$Request->command];

		if ($command->hidden_command === TRUE) {
			throw new Exception("Command not found " . $Request->command);
		}

		$possible_args = $command->args;

		$has        = [];
		$cli_params = [];

		foreach($possible_args as $title => $arg) {
			if (isset($Request->arguments[$title])) {
				$arg_passed              = $Request->arguments[$title];
				$has[]                   = $title;
				$cli_params[$arg->title] = $this->processArgument($arg, $arg_passed);
				continue;
			}

			if (isset($Request->arguments[$arg->short_name])) {
				$arg_passed              = $Request->arguments[$arg->short_name];
				$has[]                   = $arg->title;
				$cli_params[$arg->title] = $this->processArgument($arg, $arg_passed);
				continue;
			}

			if ($arg->required && is_null($arg->default_value)) {
				throw new Exception('Missing required argument ' . $arg->title);
			} else {
				$cli_params[$arg->title] = $arg->default_value;
			}
		}

		return [$command, $cli_params];
	}

	/**
	 * Process an argument
	 *
	 * @param mixed $arg    arg object
	 * @param mixed $arg_in arg value
	 * @return mixed
	 */
	protected function processArgument($arg, $arg_in) {
		if (isset($arg->set_value)) {
			return $arg->set_value;
		}
		
		return $arg_in->value[0];
	}
}