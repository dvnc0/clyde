<?php
namespace Clyde\Actions;

use Clyde\Application;
use Clyde\Core\Event_Dispatcher;
use Clyde\Request\Request;
use Clyde\Request\Request_Response;
use Clyde\Tools\Printer;
use Exception;

/**
 * @property Injector SInjector
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
	 * Event dispatcher
	 *
	 * @var Event_Dispatcher
	 */
	protected Event_Dispatcher $Event_Dispatcher;

	/**
	 * Printer
	 *
	 * @var Printer
	 */
	protected Printer $Printer;

	/**
	 * Construct
	 *
	 * @param Application      $Application      The Application instance
	 * @param Event_Dispatcher $Event_Dispatcher The Event Dispatcher
	 */
	public function __construct(Application $Application, Event_Dispatcher $Event_Dispatcher) {
		$this->Application      = $Application;
		$this->Event_Dispatcher = $Event_Dispatcher;
		$this->Printer          = $this->Application->Printer;
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
	 * @param string $name      Method name
	 * @param array  $arguments Method arguments
	 * @return mixed
	 */
	public function __get(string $name) {
		$method = "get{$name}";
		if (method_exists($this->Application, $method)) {
			return $this->Application->$method();
		}

		throw new Exception("Method {$method} does not exist");
	}
}