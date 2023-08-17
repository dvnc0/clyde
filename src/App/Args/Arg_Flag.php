<?php

namespace Clyde\Args;

use Clyde\Objects\Flag_Object;

class Arg_Flag
{
	/**
	 * Flag data object
	 *
	 * @var Flag_Object
	 */
	protected Flag_Object $Flag_Object;

	/**
	 * Constructor
	 *
	 * @param string $title Arg title
	 */
	public function __construct(string $title) {
		$this->Flag_Object        = new Flag_Object;
		$this->Flag_Object->title = $title;
	}

	/**
	 * Create a new arg static helper
	 *
	 * @param string $title the arg title
	 * @return Arg_Flag
	 */
	public static function create(string $title): Arg_Flag {
		return new Arg_Flag($title);
	}

	/**
	 * The long name of the arg ie: verbose
	 *
	 * @param string $long_name long name of argument
	 * @return Arg_Flag
	 */
	public function longName(string $long_name): Arg_Flag {
		$this->Flag_Object->long_name = $long_name;
		return $this;
	}

	/**
	 * Short name of argument ie: v
	 *
	 * @param string $short_name short name of argument
	 * @return Arg_Flag
	 */
	public function shortName(string $short_name): Arg_Flag {
		$this->Flag_Object->short_name = $short_name;
		return $this;
	}

	/**
	 * The flags default value
	 *
	 * @param boolean $default_value default value
	 * @return Arg_Flag
	 */
	public function defaultValue(bool $default_value): Arg_Flag {
		$this->Flag_Object->default_value = $default_value;
		return $this;
	}

	/**
	 * Is the argument required or not
	 *
	 * @param boolean $required is the argument required
	 * @return Arg_Flag
	 */
	public function required(bool $required): Arg_Flag {
		$this->Flag_Object->required = $required;
		return $this;
	}

	/**
	 * Help information
	 *
	 * @param string $help the information that should display in the help command
	 * @return Arg_Flag
	 */
	public function help(string $help): Arg_Flag {
		$this->Flag_Object->help = $help;
		return $this;
	}

	/**
	 * The value to set the flag to if present
	 *
	 * @param boolean $value value when flag is present
	 * @return Arg_Flag
	 */
	public function setTo(bool $value): Arg_Flag {
		$this->Flag_Object->set_value = $value;
		return $this;
	}

	/**
	 * save the object
	 *
	 * @return Flag_Object
	 */
	public function save(): Flag_Object {
		return $this->Flag_Object;
	}
}