<?php
namespace Clyde\Core;

use Clyde\Request\Request;
use Clyde\Request\Request_Item;
use Exception;

class Request_Handler
{

	/**
	 * argument tokens
	 *
	 * @var array
	 */
	protected array $tokens = [
		'short' => "/(?:-)([a-zA-z]{1})/",
		'long' => "/(?:--)([a-zA-z]+).*/",
	];

	/**
	 * next key in args array
	 *
	 * @var integer
	 */
	protected int $next_key = 0;

	/**
	 * parse the request from argv
	 *
	 * @param array $argv argv array
	 * @return Request
	 */
	public function parseRequest(array $argv): Request {
		$Request = new Request;

		if (empty($argv[1])) {
			throw new Exception("Command was not passed");
		}
		$Request->command = $argv[1];

		$passed_arguments = $this->getArgumentsPassed($argv);
 
		$Request->arguments = $passed_arguments;
		return $Request;
	}

	/**
	 * get the arguments passed
	 *
	 * @param array $argv argv array
	 * @return array
	 */
	protected function getArgumentsPassed(array $argv): array {
		$args_out     = [];
		$argv_mutated = array_slice($argv, 2, (count($argv) - 1));

		if(empty($argv_mutated)) {
			return $args_out;
		}

		if (strpos($argv_mutated[0], '-') === false) {
			$argv_mutated = array_slice($argv_mutated, 1, (count($argv) - 1));
		}

		if(empty($argv_mutated)) {
			return $args_out;
		}

		$current_arg = NULL;
		$ignore_key  = FALSE;
		for ($key = 0; $key <= (count($argv_mutated) - 1); $key++) {
			$value = $argv_mutated[$key];
			if (preg_match($this->tokens['short'], $value) > 0 || preg_match($this->tokens['long'], $value) > 0) {
				$current_arg = str_replace('-', '', $value);
				$value_extra = NULL;

				if (str_contains($current_arg, '=')) {
					[$current_arg, $value_extra] = explode('=', $current_arg);
				}
				$Request_Item           = new Request_Item;
				$Request_Item->argument = $current_arg;
				
				$value = $this->findUntilNextArgument($argv_mutated, $key);

				if(!is_null($value_extra)) {
					$value = empty($value) ? [] : $value;
					array_unshift($value, $value_extra);
				}

				$Request_Item->value               = $value;
				$args_out[$Request_Item->argument] = $Request_Item;
				$key                               = $this->next_key - 1;
				
				if ($this->next_key === (count($argv_mutated) - 1) && $ignore_key === TRUE) {
					$key = $this->next_key;
				}

				if ($this->next_key === (count($argv_mutated) - 1) && $ignore_key === FALSE) {
					$ignore_key = TRUE;
				}

				continue;
			}
		}

		return $args_out;
	}

	/**
	 * Find next argument
	 *
	 * @param array   $argv_mutated mutated array
	 * @param integer $key          current position
	 * @return array|null
	 */
	protected function findUntilNextArgument(array $argv_mutated, int $key): array|null {
		$index = $key + 1;
		$out   = [];

		for($index; $index <= (count($argv_mutated) - 1);$index++) {

			$value          = $argv_mutated[$index];
			$this->next_key = $index;

			if (preg_match($this->tokens['short'], $value) > 0 || preg_match($this->tokens['long'], $value) > 0) {
				return empty($out) ? NULL : $out;
			}

			if (str_contains($value, '=')) {
				continue;
			}

			array_push($out, $value);
			continue;
		}

		return empty($out) ? NULL : $out;
	}
}