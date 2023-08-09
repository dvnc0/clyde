<?php
namespace Clyde\Request;

class Request {
    public array $arguments = [];
    public string $command;

    public function getArgument(string $key) {
        return $this->arguments[$key] ?? null;
    }

    public function getAllArguments(): array {
        return $this->arguments;
    }
}