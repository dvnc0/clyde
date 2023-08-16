<?php
namespace Clyde\Commands;

use Clyde\Actions\Action_Base;
use Clyde\Objects\Command_Object;
use Clyde\Objects\Flag_Object;
use Clyde\Objects\Option_Object;
use Exception;

class Command {
    /**
     * Command Object 
     *
     * @var Command_Object
     */
    protected Command_Object $Command_Object;

    /**
     * Constructor
     *
     * @param string $command_name the command name/title
     */
    public function __construct(string $command_name){
        $this->Command_Object = new Command_Object;
        $this->Command_Object->command_name = $command_name;
    }

    /**
     * Creates a new command
     *
     * @param string $command_name the command name/title
     * @return Command
     */
    public static function create(string $command_name): Command {
        return new Command($command_name);
    }

    /**
     * Add about information to the command
     *
     * @param string $command_information the information that help should show about this command
     * @return Command
     */
    public function about(string $command_information): Command {
        $this->Command_Object->about = $command_information;
        return $this;
    }

    /**
     * Add an action to a command
     *
     * @param string|callable $action Either an instance of Action_Base or a callable/anonymous function
     * @return Command
     */
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

    /**
     * Add an argument to the command
     *
     * @param Flag_Object|Option_Object $Arg_Object either a boolean flag (-v) or an option (--name=John Smith)
     * @return Command
     */
    public function arg(Flag_Object|Option_Object $Arg_Object): Command {
        $this->Command_Object->args[$Arg_Object->title] = $Arg_Object;
        return $this;
    }

    /**
     * Subscribe to an event that can be triggered by other commands
     *
     * @param string $event_name the name of the event to subscribe to
     * @return Command
     */
    public function subscribe(string $event_name): Command {
        $this->Command_Object->event = $event_name;
        return $this;
    }

    /**
     * Returns the command object
     *
     * @return Command_Object
     */
    public function save(): Command_Object {
        return $this->Command_Object;
    }

}