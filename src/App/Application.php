<?php

namespace Clyde;

use Clyde\Actions\Action_Base;
use Clyde\Commands\Command;
use Clyde\Objects\Application_Object;
use Clyde\Objects\Command_Object;
use Clyde\Tools\Help;
use Clyde\Core\Request_Handler;
use Clyde\Core\Command_Parser;
use Clyde\Request\Request;
use Clyde\Core\Event_Dispatcher;
use Clyde\Objects\Printer_Object_Base;
use Clyde\Tools\Printer;
use Exception;

class Application {
    protected Application_Object $Application_Object;
    protected Request_Handler $Request_Handler;
    protected Command_Parser $Command_Parser;
    public Event_Dispatcher $Event_Dispatcher;
    public Printer $Printer;
    protected Help $Help;
    protected array $argv;

    protected static Application|null $Instance = null;

    public function __construct() {
        $this->Application_Object = new Application_Object;
        $this->Request_Handler = new Request_Handler;
        $this->Command_Parser = new Command_Parser;
        $this->Event_Dispatcher = new Event_Dispatcher($this);
        $this->Printer = new Printer;
        $this->Help = new Help;
        static::$Instance = $this;
    }

    public function setCustomPrinter(Printer_Object_Base $Printer_Object): void {
        $this->Printer->customPrinter($Printer_Object);
    }

    public static function create(string $application_name): Application {
        if (is_null(static::$Instance)) {
            $Application = new Application;
            $Application->new($application_name);
            return $Application;
        }

        static::$Instance->new($application_name);
        return static::$Instance;
    }

    public function getEvents(): array {
        return $this->Application_Object->events;
    }

    public function new(string $application_name): Application {
        $this->Application_Object->application_name = $application_name;
        return $this;
    }

    public function about(string $about): Application {
        $this->Application_Object->about = $about;
        return $this;
    }

    public function author(string $author): Application {
        $this->Application_Object->author = $author;
        return $this;
    }

    public function version(string $version): Application {
        $this->Application_Object->version = $version;
        return $this;
    }

    public function website(string $website): Application {
        $this->Application_Object->website = $website;
        return $this;
    }

    public function command(Command_Object $Command_Object): Application {
        $this->Application_Object->commands[$Command_Object->command_name] = $Command_Object;
        if (!empty($Command_Object->event)) {
            $this->Application_Object->events[$Command_Object->event][] = $Command_Object;
        }
        return $this;
    }

    protected function buildVersionCommand() {
        if(empty($this->Application_Object->version)) {
            return;
        }
        $version = $this->Application_Object->version;
        $title = $this->Application_Object->application_name;
        $command = Command::create('version')
            ->about('Prints the version information for ' . $title)
            ->action(function($params) use ($version, $title) {
                echo "$title version: $version\n\n";
                exit();
            })
            ->save();
        $this->command($command);
    }

    public function run(): void {
        $this->buildVersionCommand();
        $this->argv = $_SERVER['argv'];
        $Request = $this->Request_Handler->parseRequest($this->argv);

        [$command, $cli_params] = $this->Command_Parser->buildCommandData($Request, $this->Application_Object);

        $action = $command->action;

        if (is_callable($action)) {
            call_user_func($action, $cli_params);
            return;
        }

        if (get_parent_class($action) === Action_Base::class) {
            $Request = new Request;
            $Request->command = $command->command_name;
            $Request->arguments = $cli_params;
            $c = new $action($this, $this->Event_Dispatcher);
            $c->execute($Request);
            return;
        }

        throw new Exception("Action is not callable or child of Action_Base");
    }
}