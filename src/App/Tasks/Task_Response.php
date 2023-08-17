<?php
namespace Clyde\Tasks;

class Task_Response
{
	public bool $success;
	public string $message;

	public function __construct(bool $success, string $message = '') {
		$this->success = $success;
		$this->message = $message;
	}
}