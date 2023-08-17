<?php

namespace Clyde\Objects;

class Application_Object
{
	public string $application_name = '';
	public string $about            = '';
	public string $version          = '';
	public string $author           = '';
	public string $website          = '';
	public string|null $template    = NULL;
	public array $commands;
	public array $events = [];
}