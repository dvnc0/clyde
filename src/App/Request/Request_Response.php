<?php
namespace Clyde\Request;

class Request_Response
{
	public bool $success;
	public string $message;
	public array $data;

	public function __construct(bool $success, string $message = '', array $data = []) {
		$this->success = $success;
		$this->message = $message;
		$this->data    = $data;    
	}
}