<?php
namespace Clyde\Tools;

use Clyde\Objects\Printer_Object;
use Clyde\Objects\Printer_Object_Base;
use Exception;

class Printer {
    /**
     * Printer_Object_Base
     * Defines the colored outputs for the print methods
     *
     * @var Printer_Object_Base
     */
    protected Printer_Object_Base $Printer_Object;

    /**
     * Constructor
     */
    public function __construct() {
        $this->Printer_Object = new Printer_Object;
    }

    /**
     * Load a custom Printer_Object
     *
     * @param Printer_Object_Base $Printer_Object
     * @return void
     * 
     * @codeCoverageIgnore
     */
    public function customPrinter(Printer_Object_Base $Printer_Object): void {
        $this->Printer_Object = $Printer_Object;
    }
    
    /**
     * Handles the actual formatting and printing to the screen.
     *
     * @param string  $message  the message that should be printed
     * @param string  $color    the color/styling of the message
     * @param boolean $new_line should the message end with a new line
     * @return void
     */
    protected function output(string $message, string $color, bool $new_line = true): void {
        $line_pattern = "\e[%sm%s\e[0m";
        $line_pattern = $new_line ? $line_pattern . "\n" : $line_pattern;
        $this->printOutput($line_pattern, $color, $message);
        return;
    }

    /**
     * Prints a full width message with top and bottom border
     *
     * @param string $message the message to print
     * @return string
     */
    public function fullWidthMessage(string $message): string {
        $width           = $this->getShellWidth();
        $remaining_width = $width - strlen($message);
        $message_formatted = $this->fullWidth($message);
        $message_out         = '';
        if ($remaining_width > 0) {
            $message_out = str_repeat('=', $width);
            $message_out .= "\n{$message_formatted}\n";
            $message_out .= str_repeat('=', $width);
        }

        return $message_out;
    }

    /**
     * Creates a full width message
     *
     * @param string $message The message to make full width
     * @return string
     */
    public function fullWidth(string $message): string {
        $width           = $this->getShellWidth();
        $remaining_width = $width - strlen($message);
        while ($remaining_width > 0) {
            $message .= " ";
            $remaining_width--;
        }

        return $message;
    }

    /**
     * Prints an Error message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function error(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->error, $new_line);
    }

    /**
     * Prints a Warning message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function warning(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->warning, $new_line);
    }

    /**
     * Prints an Alert message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function alert(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->alert, $new_line);
    }

    /**
     * Prints an plain message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function message(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->message, $new_line);
    }

    /**
     * Prints an Info message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function info(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->info, $new_line);
    }

    /**
     * Prints an Success message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function success(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->success, $new_line);
    }

    /**
     * Prints a full width message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function banner(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->banner, $new_line);
    }

    /**
     * Prints a Caption message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function caption(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->caption, $new_line);
    }

    /**
     * Prints a Highlighted message
     *
     * @param string  $message  The message to print
     * @param boolean $new_line If the message should end in aa new line, defaults to true
     * @return void
     */
    public function highlight(string $message, bool $new_line = true): void {
        $this->output($message, $this->Printer_Object->highlight, $new_line);
    }
    
    /**
     * Catch all for any extra print messages that may be added in custom printers
     *
     * @param string $method The method name called
     * @param array  $args   Any args, should be message
     * @return void
     */
    public function __call(string $method, array $args): void {
        $color = $this->Printer_Object->{$method} ?? null;

        if (is_null($color)) {
            throw new Exception("Color for {$method} not found");
        }

        if (empty($args)) {
            throw new Exception("No message passed to Printer method for {$method}");
        }

        $message = $args[0];

        $this->output($message, $color);
        return;
    }

    /**
     * Get the shell width
     *
     * @return integer
     * 
     * @codeCoverageIgnore
     */
    protected function getShellWidth(): int {
        return (int) (int)shell_exec('tput cols');
    }

    /**
     * Format and print message
     *
     * @param string $line_pattern The escape codes
     * @param string $color        The color/styling for the message
     * @param string $message      The message
     * @return void
     * 
     * @codeCoverageIgnore
     */
    protected function printOutput(string $line_pattern, string $color, string $message): void {
        echo sprintf($line_pattern, $color, $message);
    }
}