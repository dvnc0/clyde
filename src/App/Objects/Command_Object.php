<?php
namespace Clyde\Objects;

use Clyde\Actions\Action_Base;

/**
 * @phpstan-type CommandObject Command_Object
 * 
 */
class Command_Object
{
	/**
	 * the name of this command
	 *
	 * @var string
	 */
	public string $command_name;

	/**
	 * the about/help information that should be shown
	 *
	 * @var string
	 */
	public string $about = '';

	/**
	 * the action this command should trigger
	 *
	 * @var callable|class-string<Action_Base>
	 */
	public $action;

	/**
	 * Any passed in Argument Objects are stored here
	 *
	 * @var array
	 */
	public array $args = [];

	/**
	 * Any event name this command is subscribed too
	 *
	 * @var string
	 */
	public string $event = '';

	/**
	 * Hides a command from the help menu or CLI input
	 *
	 * @var boolean
	 */
	public bool $hidden_command = FALSE;

	/**
	 * unnamed arg passed in after command ie: foo <bar> bar would be this value
	 *
	 * @var string
	 */
	public string $command_arg = '';
}