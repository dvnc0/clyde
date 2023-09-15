<?php
namespace Clyde\Actions;

use Clyde\Application;
use Clyde\Core\Event_Dispatcher;
use Clyde\Request\Request;
use Clyde\Request\Request_Response;
use Clyde\Tools\Printer;
use Clyde\Injector\Injector;

/**
 * @property Injector $Injector
 * @property Event_Dispatcher $Event_Dispatcher
 * @property Printer $Printer
 */
abstract class Action_Base
{
	/**
	 * Application
	 *
	 * @var Application
	 */
	protected Application $Application;

	/**
	 * Construct
	 *
	 * @param Application $Application The Application instance
	 */
	public function __construct(Application $Application) {
		$this->Application = $Application;
	}

	/**
	 * Dispatch a named event
	 *
	 * @param string $event_name Event to dispatch
	 * @param array  $data       CLI data to pass through to event
	 * @return void
	 */
	protected function dispatchEvent(string $event_name, array $data = []): void {
		$this->Event_Dispatcher->dispatch($event_name, $data);
	}

	/**
	 * Execute an action
	 *
	 * @param Request $Request Request object
	 * @return Request_Response
	 */
	abstract public function execute(Request $Request): Request_Response;

	/**
	 * Call a method on the Application
	 *
	 * @param string $name Method name
	 * @return mixed
	 */
	public function __get(string $name) {
		return $this->Application->{$name};
	}
}