<?php
namespace Clyde\Commands;

use Clyde\Actions\Action_Base;
use Clyde\Objects\Command_Object;
use Clyde\Objects\Flag_Object;
use Clyde\Objects\Option_Object;
use Exception;

class Command {
    protected Command_Object $Command_Object;

    public function __construct(string $command_name){
        $this->Command_Object = new Command_Object;
        $this->Command_Object->command_name = $command_name;
    }

    public static function create(string $command_name): Command {
        return new Command($command_name);
    }

    public function about(string $command_information): Command {
        $this->Command_Object->about = $command_information;
        return $this;
    }

    public function action(string|callable $action): Command {
        if (is_callable($action)) {
            $this->Command_Object->action = $action;
            return $this;
        }

        if (class_exists($action) && get_parent_class($action) === Action_Base::class) {
            $this->Command_Object->action = $action;
            return $this;
        }
        
        throw new Exception("Passed action is not a child of Action_Base or a callable.");
    }

    public function arg(Flag_Object|Option_Object $Arg_Object): Command {
        $this->Command_Object->args[$Arg_Object->title] = $Arg_Object;
        return $this;
    }

    public function subscribe(string $event_name): Command {
        $this->Command_Object->event = $event_name;
        return $this;
    }

    public function save(): Command_Object {
        return $this->Command_Object;
    }

}