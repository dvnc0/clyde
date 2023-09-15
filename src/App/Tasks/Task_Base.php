<?php
namespace Clyde\Tasks;

use Clyde\Application;
use Clyde\Core\Event_Dispatcher;
use Clyde\Tasks\Task_Response;
use Clyde\Tools\Printer;
use Exception;

/**
 * @property Injector $Injector
 * @property Event_Dispatcher $Event_Dispatcher
 * @property Printer $Printer
 */
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
	 * construct
	 *
	 * @param Application $Application Application Instance
	 */
	public function __construct(Application $Application) {
		$this->Application = $Application;
	}
	
	/**
	 * execute task
	 *
	 * @return Task_Response
	 */
	abstract public function execute(): Task_Response;

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