<?php
namespace Clyde\Objects;

class Command_Object {
    public string $command_name;
    public string $about = '';
    public $action;
    public array $args = [];
    public string $event = '';
}