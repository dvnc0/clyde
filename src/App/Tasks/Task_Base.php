<?php
namespace Clyde\Tasks;

use Clyde\Application;
use Clyde\Core\Event_Dispatcher;
use Clyde\Tasks\Task_Response;
use Clyde\Tools\Printer;

abstract class Task_Base {
    public string $task_message = "Running task... ";
    protected Application $Application;
    protected Event_Dispatcher $Event_Dispatcher;
    protected Printer $Printer;

    public function __construct(Application $Application) {
        $this->Application = $Application;
        $this->Event_Dispatcher = $this->Application->Event_Dispatcher;
        $this->Printer = $this->Application->Printer;
    }
    
    abstract public function execute(): Task_Response;
}