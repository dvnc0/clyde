<?php
namespace Clyde\Request;

class Request_Response
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
	 * data
	 *
	 * @var array
	 */
	public array $data;

	/**
	 * construct
	 *
	 * @param boolean $success successful
	 * @param string  $message message to attach
	 * @param array   $data    any additional data
	 */
	public function __construct(bool $success, string $message = '', array $data = []) {
		$this->success = $success;
		$this->message = $message;
		$this->data    = $data;    
	}
}