<?php

use Clyde\Tools\Printer;
use PHPUnit\Framework\TestCase;
use Clyde\Objects\Printer_Object;

/**
 * @covers Clyde\Tools\Printer
 */
class Printer_Test extends TestCase {
    protected function testMessageTypes() {
        $Printer_Object = new Printer_Object;
        return [
            ['error', 'This is an error', $Printer_Object->error],
            ['warning', 'This is an warning', $Printer_Object->warning],
            ['alert', 'This is an alert', $Printer_Object->alert],
            ['message', 'This is an message', $Printer_Object->message],
            ['info', 'This is an info', $Printer_Object->info],
            ['success', 'This is an success', $Printer_Object->success],
            ['banner', 'This is an banner', $Printer_Object->banner],
            ['caption', 'This is an caption', $Printer_Object->caption],
            ['highlight', 'This is an highlight', $Printer_Object->highlight],
        ];
    }

    public function testFullWidthMessageIsFullWidth() {
        $Mock_Printer = $this->getMockBuilder(Printer::class)
            ->onlyMethods(['getShellWidth'])
            ->getMock();

        $Mock_Printer->expects($this->exactly(2))
            ->method('getShellWidth')
            ->willReturn(10);

        $message = "Foo Bar";

        $result_message_should_be = <<<PHP
        ==========
        Foo Bar   
        ==========
        PHP;

        $result = $Mock_Printer->fullWidthMessage($message);

        $this->assertIsString($result);
        $this->assertSame($result_message_should_be, $result);
    }

    /**
     * Uses data provider, runs 9 tests
     * 
     * @dataProvider testMessageTypes
     */
    public function testErrorMessage($message_type, $message, $message_color) {
        $Mock_Printer = $this->getMockBuilder(Printer::class)
            ->onlyMethods(['printOutput'])
            ->getMock();

        $Mock_Printer->expects($this->once())
            ->method('printOutput')
            ->with(
                $this->equalTo("\e[%sm%s\e[0m\n"),
                $this->equalTo($message_color),
                $this->equalTo($message)
            );
        
        $Mock_Printer->{$message_type}($message);
    }
}