<?php
namespace Clyde\Tasks;

class Task_Response
{
	/**
	 * success
	 *
	 * @var boolean
	 */
	public bool $success;

	/**
	 * message
	 *
	 * @var string
	 */
	public string $message;

	/**
	 * construct
	 *
	 * @param boolean $success was task successful
	 * @param string  $message message to attach
	 */
	public function __construct(bool $success, string $message = '') {
		$this->success = $success;
		$this->message = $message;
	}
}