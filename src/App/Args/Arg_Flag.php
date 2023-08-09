<?php

namespace Clyde\Args;

use Clyde\Objects\Flag_Object;

class Arg_Flag {
    protected Flag_Object $Flag_Object;

    public function __construct(string $title){
        $this->Flag_Object = new Flag_Object;
        $this->Flag_Object->title = $title;
    }

    public static function create(string $title): Arg_Flag {
        return new Arg_Flag($title);
    }

    public function longName(string $long_name): Arg_Flag {
        $this->Flag_Object->long_name = $long_name;
        return $this;
    }

    public function shortName(string $short_name): Arg_Flag {
        $this->Flag_Object->short_name = $short_name;
        return $this;
    }

    public function defaultValue(bool $default_value): Arg_Flag {
        $this->Flag_Object->default_value = $default_value;
        return $this;
    }

    public function required(bool $required): Arg_Flag {
        $this->Flag_Object->required = $required;
        return $this;
    }

    public function help(string $help): Arg_Flag {
        $this->Flag_Object->help = $help;
        return $this;
    }

    public function setTo(bool $value): Arg_Flag {
        $this->Flag_Object->set_value = $value;
        return $this;
    }

    public function save(): Flag_Object {
        return $this->Flag_Object;
    }
}