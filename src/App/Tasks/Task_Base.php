<?php
namespace Clyde\Tasks;

use Clyde\Application;
use Clyde\Core\Event_Dispatcher;
use Clyde\Tasks\Task_Response;
use Clyde\Tools\Printer;

abstract class Task_Base
{
	/**
	 * running message
	 *
	 * @var non-empty-string
	 */
	public string $task_message = "Running task... ";

	/**
	 * Application Instance
	 *
	 * @var Application
	 */
	protected Application $Application;

	/**
	 * Event dispatch
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
	 * construct
	 *
	 * @param Application $Application Application Instance
	 */
	public function __construct(Application $Application) {
		$this->Application      = $Application;
		$this->Event_Dispatcher = $this->Application->Event_Dispatcher;
		$this->Printer          = $this->Application->Printer;
	}
	
	/**
	 * execute task
	 *
	 * @return Task_Response
	 */
	abstract public function execute(): Task_Response;
}