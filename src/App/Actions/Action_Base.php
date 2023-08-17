<?php
namespace Clyde\Actions;

use Clyde\Application;
use Clyde\Core\Event_Dispatcher;
use Clyde\Request\Request;
use Clyde\Request\Request_Response;
use Clyde\Tools\Printer;

abstract class Action_Base
{
	protected Application $Application;
	protected Event_Dispatcher $Event_Dispatcher;
	protected Printer $Printer;

	public function __construct(Application $Application, Event_Dispatcher $Event_Dispatcher) {
		$this->Application      = $Application;
		$this->Event_Dispatcher = $Event_Dispatcher;
		$this->Printer          = $this->Application->Printer;
	}

	protected function dispatchEvent(string $event_name, array $data = []): void {
		$this->Event_Dispatcher->dispatch($event_name, $data);
	}

	abstract function execute(Request $Request): Request_Response;
}