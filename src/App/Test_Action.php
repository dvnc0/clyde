<?php

namespace Clyde;

use Clyde\Actions\Action_Base;
use Clyde\Request\Request_Response;
use Clyde\Request\Request;

class Test_Action extends Action_Base
{
	public function execute(Request $Request): Request_Response {
		var_dump($Request);
		$this->Printer->caption("TEST ALERT");
		$this->dispatchEvent('FOO:actionEvent', []);
		return new Request_Response(TRUE);
	}
}