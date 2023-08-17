<?php

namespace Clyde;

use Clyde\Actions\Action_Base;
use Clyde\Request\Request_Response;
use Clyde\Request\Request;
use Clyde\Tasks\Task_Runner;
use Clyde\Task_Test;

class Foo_Action extends Action_Base
{
	public function execute(Request $Request): Request_Response {
		echo "Foo Action!!";
		$t = new Task_Runner($this->Application);
		$t->run(new Task_Test($this->Application));
		return new Request_Response(TRUE);
	}
}