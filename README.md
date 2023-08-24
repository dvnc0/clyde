# Clyde
Your friendly neighborhood CLI framework.

[![PHPUnit](https://github.com/dvnc0/clyde/actions/workflows/php.yml/badge.svg)](https://github.com/dvnc0/clyde/actions/workflows/php.yml)
[![PHPStan](https://github.com/dvnc0/clyde/actions/workflows/stan.yml/badge.svg)](https://github.com/dvnc0/clyde/actions/workflows/stan.yml)

**Documentation is incomplete**
[Wiki](https://github.com/dvnc0/clyde/wiki)
**This is still a WIP**

## Purpose
A small framework that tries to stay out of the way and help you rapidly build command line applications.

## The most basic app
The flexibility of the framework allows you to build applications in a number of ways, the simplest method is shown below.

```php
<?php
if (php_sapi_name() !== 'cli') {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use Clyde\Application;
use Clyde\Commands\Command;

Application::create('devtool')
    ->about('A small developer tool to help do some cool things')
    ->version('1.0.0')
    ->author('dvnc0')
    ->website('https://github.com/dvnc0')
    ->command(
		Command::create('encode <string>')
		->about('Encode the passed string with base64')
		->action(function($args) {
			print(base64_encode($args['string']));
			exit(0);
		})
		->save()
	)
    ->run();
```

This will create the help pages, routes, and process the command using `php devtool encode "Hello World"`.

### It's flexible
The Application expects that the method `Command` will return a `Command_Object` this patter follows for Commands and Args as well. This allows you to abstract away the creation of Commands and Args to additional classes if needed. As long as the commands return a valid `Command_Object` everything will be fine. Essentially, the Application is a collection of Objects.

## Commands
Command actions should extend `Clyde\Actions\Action_Base` or be a callable function. Callable functions will be given an array of arguments and classes extending `Action_Base` are given a Request object.

The command `version` is automatically added as well as the `help` command. Commands also automatically get the `--help` argument which displays their help information.

## Tasks and Task Runner
Commands can run tasks that extend `Clyde\Tasks\Task_Base` via the Task_Runner. This will fork the task into a new process and add a CLI progress spinner while the task executes.

## Helpers
The project also includes some helper classes for printing tables, interactive user prompts, styled printing, and adding Emojis.