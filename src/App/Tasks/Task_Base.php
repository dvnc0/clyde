<?php
namespace Clyde\Tasks;

use Clyde\Tasks\Task_Response;

abstract class Task_Base {
    public string $task_message = "Running task... ";
    
    abstract public function execute(): Task_Response;
}