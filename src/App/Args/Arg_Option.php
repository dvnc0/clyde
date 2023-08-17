<?php

namespace Clyde\Args;

use Clyde\Objects\Option_Object;

class Arg_Option
{
	protected Option_Object $Option_Object;

	public function __construct(string $title) {
		$this->Option_Object        = new Option_Object;
		$this->Option_Object->title = $title;
	}

	public static function create(string $title): Arg_Option {
		return new Arg_Option($title);
	}

	public function longName(string $long_name): Arg_Option {
		$this->Option_Object->long_name = $long_name;
		return $this;
	}

	public function shortName(string $short_name): Arg_Option {
		$this->Option_Object->short_name = $short_name;
		return $this;
	}

	public function defaultValue(string $default_value): Arg_Option {
		$this->Option_Object->default_value = $default_value;
		return $this;
	}

	public function required(bool $required): Arg_Option {
		$this->Option_Object->required = $required;
		return $this;
	}

	public function help(string $help): Arg_Option {
		$this->Option_Object->help = $help;
		return $this;
	}

	public function save(): Option_Object {
		return $this->Option_Object;
	}
}