<?php

use Clyde\Commands\Command;
use Clyde\Objects\Command_Object;
use PHPUnit\Framework\TestCase;

/**
 * @covers Clyde\Commands\Command
 */
class Command_Test extends TestCase {
    public function testStaticCreateReturnsInstance() {
        $command = Command::create('Foo');

        $this->assertInstanceOf(Command::class, $command);
    }

    public function testCommandObjectBuildsWithCallable() {
        $command = Command::create('Foo')
            ->about('Foo About')
            ->subscribe('submit:FooEvent')
            ->action(function() {echo 'Foo Action';})
            ->save();
        
        $this->assertInstanceOf(Command_Object::class, $command);
        $this->assertIsCallable($command->action);
        $this->assertSame('Foo', $command->command_name);
        $this->assertSame('Foo About', $command->about);
        $this->assertSame('submit:FooEvent', $command->event);
    }

    public function testExceptionIsThrownOnAction() {
        $command = Command::create('Foo');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Passed action is not a child of Action_Base or a callable.");
        $command->action('Foo Bar');
    }
}
