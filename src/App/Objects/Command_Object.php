<?php
namespace Clyde\Objects;

use Clyde\Actions\Action_Base;

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
	 * @var callable|Action_Base
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
}