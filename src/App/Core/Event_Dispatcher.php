<?php
namespace Clyde\Core;

use Clyde\Application;
use Exception;
use Clyde\Request\Request;
use Clyde\Actions\Action_Base;

class Event_Dispatcher
{
	/**
	 * Application
	 *
	 * @var Application
	 */
	protected Application $Application;

	/**
	 * Events that have been subscribed too
	 *
	 * @var array
	 */
	protected array $events = [];

	/**
	 * Construct
	 *
	 * @param Application $Application Application instance
	 */
	public function __construct(Application $Application) {
		$this->Application = $Application;
	}

	/**
	 * Dispatch an event
	 *
	 * @param string $event_name the name of the event to dispatch
	 * @param array  $data       the argument data to pass
	 * @return void
	 */
	public function dispatch(string $event_name, array $data = []) {
		
		$this->events = $this->Application->getEvents();
		if (empty($this->events) || empty($this->events[$event_name])) {
			throw new Exception("Event $event_name not found!");
		}

		foreach ($this->events[$event_name] as $Command) {
			if (is_callable($Command)) {
				call_user_func($Command, $data);
				continue;
			}
	
			if (get_parent_class($Command->action) === Action_Base::class) {
				$action             = $Command->action;
				$Request            = new Request;
				$Request->command   = $Command->command_name;
				$Request->arguments = $data;
				$c                  = new $action($this->Application, $this);
				$c->execute($Request);
				continue;
			}
		}
	}
}