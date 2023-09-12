<?php

namespace Clyde\Actions;

use Clyde\Actions\Action_Base;
use Clyde\Request\Request;
use Clyde\Request\Request_Response;
use Clyde\Tools\Input;

class Test_Action extends Action_Base {
	public function execute(Request $Request): Request_Response {
		$input = $this->Injector->resolve(Input::class, ['Printer' => $this->Printer]);
		$input->get('What is the answer');
		$this->Printer->message("Test Action");
		return new Request_Response(true);
	}
}
