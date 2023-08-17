<?php
namespace Clyde;

use Clyde\Tasks\Task_Base;
use Clyde\Tasks\Task_Response;

class Task_Test extends Task_Base
{
	public function execute(): Task_Response {
		sleep(5);
		return new Task_Response(TRUE);
	}
}