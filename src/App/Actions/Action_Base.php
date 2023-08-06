<?php
namespace Clyde\Actions;

use Clyde\Request\Request;
use Clyde\Request\Request_Response;

abstract class Action_Base {
    abstract function execute(Request $Request): Request_Response;
}