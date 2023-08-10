# Clyde
Your friendly neighborhood CLI framework.

**Documentation is incomplete**
**This is still a WIP**

## Purpose
A small framework that tries to stay out of the way and let you build command line applications in a fast and simple way.

## The most basic app
The flexibility of the framework allows you to build applications in a number of ways, the simplest is shown below.

```php
#! /usr/bin/php
<?php
if (php_sapi_name() !== 'cli') {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use Clyde\Application;
use Clyde\Args\Arg_Default;
use Clyde\Args\Arg_Flag;
use Clyde\Args\Arg_Option;
use Clyde\Commands\Command;

// use statements for your project...

Application::create('runner')
    ->author('fooDev')
    ->version('0.1.0')
    ->about("A simple PHP CLI app for testing cURL")
    ->command(
        Command::create('get')
        ->about('This is a GET command')
        ->action(Get_Action::class)
        ->arg(
            Arg_Option::create('url')
            ->longName('url')
            ->shortName('u')
            ->required(true)
            ->save()
        )
        ->save()
    )
    ->command(
        Command::create('post')
        ->about('POST command')
        ->action(Post_Action::class)
        ->arg(
            Arg_Flag::create('verbose')
            ->longName('verbose')
            ->shortName('v')
            ->setTo(true)
            ->save()
        )
        ->arg(
            Arg_Flag::create('dry_run')
            ->longName('dry')
            ->shortName('d')
            ->setTo(true)
            ->required(true)
            ->defaultValue(false)
            ->save()
        )
        ->arg(
            Arg_Option::create('url')
            ->longName('url')
            ->shortName('u')
            ->required(true)
            ->save()
        )
        ->save()
    )
    ->run();
```

This will handle setting up the routes, arguments, default values etc. 

### It's flexible
The Application expects that the method `Command` will return a `Command_Object` this patter follows for Commands and Args as well. This allows you to abstract away the creation of Commands and Args to additional classes if needed. As long as the commands return a valid `Command_Object` everything will be fine.

## Commands
Command actions should extend `Clyde\Actions\Action_Base` or be a callable function. Callable functions will be given an array of arguments and classes extending `Action_Base` are given a Request object.

The command `version` is automatically added.

## Tasks and Task Runner
Commands can run tasks that extend `Clyde\Tasks\Task_Base` via the Task_Runner. This will fork the task into a new process and add a CLI progress spinner while the task executes.


## TODO
- A lot of cleanup/tests etc
- Colored output
- Helper classes for tables/progress bars etc
- Automatic Help page generation
- Better exceptions