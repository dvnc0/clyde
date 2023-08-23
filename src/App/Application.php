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

/**
 * @phpstan-import-type CommandObject from \Clyde\Objects\Command_Object
 */
class Application
{
	/**
	 * Application Object
	 *
	 * @var Application_Object
	 */
	protected Application_Object $Application_Object;

	/**
	 * Request handler
	 *
	 * @var Request_Handler
	 */
	protected Request_Handler $Request_Handler;

	/**
	 * Command Parser
	 *
	 * @var Command_Parser
	 */
	protected Command_Parser $Command_Parser;

	/**
	 * Help generator
	 *
	 * @var Help
	 */
	protected Help $Help;

	/**
	 * Args passed over cli
	 *
	 * @var array
	 */
	protected array $argv;

	/**
	 * Application instance
	 *
	 * @var Application|null
	 */
	protected static Application|null $Instance = NULL;

	/**
	 * Event dispatcher
	 *
	 * @var Event_Dispatcher
	 */
	public Event_Dispatcher $Event_Dispatcher;

	/**
	 * Printer
	 *
	 * @var Printer
	 */
	public Printer $Printer;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->Application_Object = new Application_Object;
		$this->Request_Handler    = new Request_Handler;
		$this->Command_Parser     = new Command_Parser;
		$this->Event_Dispatcher   = new Event_Dispatcher($this);
		$this->Printer            = new Printer;
		$this->Help               = new Help;
		static::$Instance         = $this;
	}

	/**
	 * Add a custom printer to the printer
	 *
	 * @param Printer_Object_Base $Printer_Object_Base Custom printer data
	 * @return void
	 */
	public function setCustomPrinter(Printer_Object_Base $Printer_Object_Base): void {
		$this->Printer->customPrinter($Printer_Object_Base);
	}

	/**
	 * Create an Application Instance
	 *
	 * @param string $application_name the applications name
	 * @return Application
	 */
	public static function create(string $application_name): Application {
		if (is_null(self::$Instance)) {
			$Application = new Application;
			$Application->new($application_name);
			self::$Instance = $Application;
			return self::$Instance;
		}

		self::$Instance->new($application_name);
		return self::$Instance;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array<string, list<CommandObject|callable>>
	 */
	public function getEvents(): array {
		return $this->Application_Object->events;
	}

	/**
	 * Create a new application
	 *
	 * @param string $application_name the applications name
	 * @return Application
	 */
	public function new(string $application_name): Application {
		$this->Application_Object->application_name = $application_name;
		return $this;
	}

	/**
	 * Add about information
	 *
	 * @param string $about about information
	 * @return Application
	 */
	public function about(string $about): Application {
		$this->Application_Object->about = $about;
		return $this;
	}

	/**
	 * Author info
	 *
	 * @param string $author author information
	 * @return Application
	 */
	public function author(string $author): Application {
		$this->Application_Object->author = $author;
		return $this;
	}

	/**
	 * Version information
	 *
	 * @param string $version current version
	 * @return Application
	 */
	public function version(string $version): Application {
		$this->Application_Object->version = $version;
		return $this;
	}

	/**
	 * Website for application
	 *
	 * @param string $website website
	 * @return Application
	 */
	public function website(string $website): Application {
		$this->Application_Object->website = $website;
		return $this;
	}

	/**
	 * Add a command to the application
	 *
	 * @param Command_Object $Command_Object the command object to add
	 * @return Application
	 */
	public function command(Command_Object $Command_Object): Application {
		$this->Application_Object->commands[$Command_Object->command_name] = $Command_Object;
		if (!empty($Command_Object->event)) {
			$this->Application_Object->events[$Command_Object->event][] = $Command_Object;
		}
		return $this;
	}

	/**
	 * Builds a version command
	 * 
	 * WIP: only build if one not created
	 *
	 * @return void
	 */
	protected function buildVersionCommand() {
		if(empty($this->Application_Object->version)) {
			return;
		}
		$version = $this->Application_Object->version;
		$title   = $this->Application_Object->application_name;

		$command = Command::create('version')
			->about('Prints the version information for ' . $title)
			->action(
				function($params) use ($version, $title) {
					echo "$title version: $version\n\n";
					exit();
				}
			)
			->save();

		$this->command($command);
	}

	/**
	 * Builds the main help command for the Application
	 *
	 * @return void
	 */
	protected function buildMainHelpCommand(): void {
		$command_help = $this->Help->buildHelpOutPut($this->Application_Object);

		$command = Command::create('help')
			->about('Prints this help information')
			->action(function () use ($command_help) {
				printf($command_help);
				return;
			})
			->save();

		$this->command($command);

	}

	/**
	 * Before run completes this should be done
	 *
	 * @return void
	 */
	protected function before(): void {
		$this->argv = $_SERVER['argv'];
		$this->buildVersionCommand();

		if(empty($this->Application_Object->commands['help'])) {
			$this->buildMainHelpCommand();
		}
	}

	/**
	 * Run the application
	 *
	 * @return void
	 */
	public function run(): void {
		$this->before();
		$Request = $this->Request_Handler->parseRequest($this->argv);

		[$command, $cli_params] = $this->Command_Parser->buildCommandData($Request, $this->Application_Object);

		$action = $command->action;

		if (!empty($cli_params['help'])) {
			print($cli_params['help']);
			return;
		}

		if (is_callable($action)) {
			call_user_func($action, $cli_params);
			return;
		}

		if (get_parent_class($action) === Action_Base::class) {
			$Request            = new Request;
			$Request->command   = $command->command_name;
			$Request->arguments = $cli_params;
			$c                  = new $action($this, $this->Event_Dispatcher);
			$c->execute($Request);
			return;
		}

		throw new Exception("Action is not callable or child of Action_Base");
	}
}