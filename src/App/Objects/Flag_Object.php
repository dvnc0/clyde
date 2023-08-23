<?php

namespace Clyde\Objects;

class Flag_Object
{
	public string $long_name;
	public string $short_name;
	public ?bool $default_value = NULL;
	public bool $required       = FALSE;
	public string $help;
	public string $title;
	public bool $set_value = TRUE;
	public bool $is_flag   = TRUE;
}