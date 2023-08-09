<?php

namespace Clyde;

use Clyde\Objects\Application_Object;
use Clyde\Objects\Command_Object;
use Clyde\Tools\Help;
use Clyde\Core\Request_Handler;

class Application {
    protected Application_Object $Application_Object;
    protected Request_Handler $Request_Handler;
    protected Help $Help;
    protected array $argv;

    protected static Application|null $Instance = null;

    public function __construct() {
        $this->Application_Object = new Application_Object;
        $this->Request_Handler = new Request_Handler;
        $this->Help = new Help;
        static::$Instance = $this;
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
        return $this;
    }

    public function run(): void {
        $this->argv = $_SERVER['argv'];
        $Request = $this->Request_Handler->parseRequest($this->argv, $this->Application_Object);
        var_dump($this->Application_Object->commands['load']);
        var_dump($Request);
    }
}