<?php
namespace Clyde\Tools;

use Clyde\Objects\Printer_Object;
use Clyde\Objects\Printer_Object_Base;
use Exception;

class Printer {
    protected Printer_Object $Printer_Object;
    public function __construct() {
        $this->Printer_Object = new Printer_Object;
    }

    public function customPrinter(Printer_Object_Base $Printer_Object): void {
        $this->Printer_Object = $Printer_Object;
    }
    
    protected function output(string $message, string $color, bool $new_line = true): void {
        if (!$new_line) {
            echo sprintf("\e[%sm%s\e[0m", $color, $message);
            return;
        }
        echo sprintf("\e[%sm%s\e[0m\n", $color, $message);
        return;
    }

    public function fullWidthMessage(string $message_in): string {
        $width           = (int)shell_exec('tput cols');
        $remaining_width = $width - strlen($message_in);
        $message         = '';
        if ($remaining_width > 0) {
            $message = str_repeat('=', $width);
            $message .= "\n{$message_in}\n";
            $message .= str_repeat('=', $width);
        }

        return $message;
    }

    public function error(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->error, $new_line);
    }

    public function warning(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->warning, $new_line);
    }

    public function alert(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->alert, $new_line);
    }

    public function message(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->message, $new_line);
    }

    public function info(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->info, $new_line);
    }

    public function success(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->success, $new_line);
    }

    public function banner(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->banner, $new_line);
    }

    public function caption(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->caption, $new_line);
    }
    
    public function __call(string $method, array $args): void {
        $color = $this->Printer_Object->{$method} ?? null;

        if (is_null($color)) {
            throw new Exception("Color for {$method} not found");
        }

        $message = $args[0];

        if ($method === 'banner') {
            $message = $this->fullWidthMessage($message);
        }

        $this->output($message, $color);
        return;
    }
}