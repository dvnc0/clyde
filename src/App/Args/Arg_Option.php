<?php

namespace Clyde\Args;

use Clyde\Objects\Option_Object;

class Arg_Option
{
	/**
	 * Arg Option object
	 *
	 * @var Option_Object
	 */
	protected Option_Object $Option_Object;

	/**
	 * Construct
	 *
	 * @param string $title The option title
	 */
	public function __construct(string $title) {
		$this->Option_Object        = new Option_Object;
		$this->Option_Object->title = $title;
	}

	/**
	 * Static helper for creating an option Argument
	 *
	 * @param string $title the arguments title
	 * @return Arg_Option
	 */
	public static function create(string $title): Arg_Option {
		return new Arg_Option($title);
	}

	/**
	 * The arguments long name ie: verbose
	 *
	 * @param string $long_name long name of the argument
	 * @return Arg_Option
	 */
	public function longName(string $long_name): Arg_Option {
		$this->Option_Object->long_name = $long_name;
		return $this;
	}

	/**
	 * The short name of the argument ie: v
	 *
	 * @param string $short_name arguments short name
	 * @return Arg_Option
	 */
	public function shortName(string $short_name): Arg_Option {
		$this->Option_Object->short_name = $short_name;
		return $this;
	}

	/**
	 * Default value of the argument
	 *
	 * @param string $default_value the default value to use
	 * @return Arg_Option
	 */
	public function defaultValue(string $default_value): Arg_Option {
		$this->Option_Object->default_value = $default_value;
		return $this;
	}

	/**
	 * Is the argument required
	 *
	 * @param boolean $required is the argument required
	 * @return Arg_Option
	 */
	public function required(bool $required): Arg_Option {
		$this->Option_Object->required = $required;
		return $this;
	}

	/**
	 * Help information
	 *
	 * @param string $help help text for the argument
	 * @return Arg_Option
	 */
	public function help(string $help): Arg_Option {
		$this->Option_Object->help = $help;
		return $this;
	}

	/**
	 * Save the option object
	 *
	 * @return Option_Object
	 */
	public function save(): Option_Object {
		return $this->Option_Object;
	}
}