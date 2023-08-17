<?php
namespace Clyde\Request;

class Request
{
	/**
	 * Arguments passed
	 *
	 * @var array
	 */
	public array $arguments = [];

	/**
	 * Command called
	 *
	 * @var string
	 */
	public string $command;

	/**
	 * Get an argument
	 *
	 * @param string $key key to get
	 * @return mixed
	 */
	public function getArgument(string $key) {
		return $this->arguments[$key] ?? NULL;
	}

	/**
	 * get all the arguments
	 *
	 * @return array
	 */
	public function getAllArguments(): array {
		return $this->arguments;
	}
}