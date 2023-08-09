<?php
namespace Clyde\Core;

use Clyde\Objects\Application_Object;
use Clyde\Request\Request;
use Clyde\Request\Request_Item;

class Request_Handler {

    protected array $tokens = [
        'short' => "/(?:-)([a-zA-z]{1})/",
        'long' => "/(?:--)([a-zA-z]+).*/",
    ];

    protected int $next_key = 0;

    public function parseRequest(array $argv, Application_Object $application_Object): Request {
        $Request = new Request;
        $Request->command = $argv[1];

        $passed_arguments = $this->getArgumentsPassed($argv);
 
        $Request->arguments = $passed_arguments;
        return $Request;
    }

    protected function getArgumentsPassed(array $argv): array {
        $args_out = [];
        $argv_mutated = array_slice($argv, 2, (count($argv) - 1));

        if(empty($argv_mutated)) {
            return $args_out;
        }

        $current_arg = null;
        $ignore_key = false;
        for ($key = 0; $key <= (count($argv_mutated) - 1); $key++) {
            $value = $argv_mutated[$key];
            if (preg_match($this->tokens['short'], $value) > 0 || preg_match($this->tokens['long'], $value) > 0) {
                $current_arg = str_replace('-', '', $value);
                $value_extra = null;

                if (str_contains($current_arg, '=')) {
                    [$current_arg, $value_extra] = explode('=', $current_arg);
                }
                $Request_Item = new Request_Item;
                $Request_Item->argument = $current_arg;
                
                $value = $this->findUntilNextArgument($argv_mutated, $key);

                if(!is_null($value_extra)) {
                    $value = empty($value) ? [] : $value;
                    array_unshift($value, $value_extra);
                }

                $Request_Item->value = $value;
                $args_out[$Request_Item->argument] = $Request_Item;
                $key = $this->next_key - 1;
                
                if ($this->next_key === (count($argv_mutated) - 1) && $ignore_key === true) {
                    $key = $this->next_key;
                }

                if ($this->next_key === (count($argv_mutated) - 1) && $ignore_key === false) {
                    $ignore_key = true;
                }

                continue;
            }
        }

        return $args_out;
    }

    protected function findUntilNextArgument(array $argv_mutated, int $key): array|null {
        $index = $key + 1;
        $out = [];

        for($index; $index <= (count($argv_mutated) - 1);$index++) {

            $value = $argv_mutated[$index];
            $this->next_key = $index;

            if (preg_match($this->tokens['short'], $value) > 0 || preg_match($this->tokens['long'], $value) > 0) {
                return empty($out) ? null : $out;
            }

            if (str_contains($value, '=')) {
                continue;
            }

            array_push($out, $value);
            continue;
        }

        return empty($out) ? null : $out;
    }
}