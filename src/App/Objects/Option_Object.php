<?php

namespace Clyde\Objects;

class Option_Object {
    public string $long_name;
    public string $short_name;
    public string $default_value;
    public bool $required = false;
    public string $help;
    public string $title;
}