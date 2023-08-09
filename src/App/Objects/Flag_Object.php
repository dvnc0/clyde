<?php

namespace Clyde\Objects;

class Flag_Object {
    public string $long_name;
    public string $short_name;
    public bool $default_value;
    public bool $required = false;
    public string $help;
    public string $title;
    public bool $set_value = true;
}