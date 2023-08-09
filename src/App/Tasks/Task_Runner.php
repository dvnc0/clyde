<?php
namespace Clyde\Tasks;

use Clyde\Tasks\Task_Base;

class Task_Runner {
    /**
     * symbols to use for animation
     *
     * @var array
     */
    protected array $symbols = [];

    public function __construct($symbols = []){
        if (!empty($symbols)) {
            $this->symbols = $symbols;
        } else {
            $this->symbols = mb_str_split('⢿⣻⣽⣾⣷⣯⣟⡿');
        }
    }

    /**
     * Execute the task with inline spinner
     *
     * @param Task_Base $Task
     * @return void
     */
    public function spin(Task_Base $Task) {
        $this->hideCursor();

        $pid = pcntl_fork();

        if ($pid === -1) {
            die("Could not fork.");
        } elseif ($pid === 0) {
            $Task->execute();
            exit(0);
        } else {
            $index = 0;
            $running = true;
            
            pcntl_signal(SIGTERM, function ($signo) use (&$running) {
                $running = false;
            });

            pcntl_signal(SIGHUP, function ($signo) use (&$running) {
                $running = false;
            });

            while ($running) {
                $this->overWriteLine();
                $symbol = $this->symbols[$index];
                $message = $Task->task_message;
                $this->write("$message {$symbol}");
                $index = ($index === (count($this->symbols) - 1)) ? 0 : $index + 1;
                $result = pcntl_waitpid($pid, $status, WNOHANG);

                if ($result === -1 || $result > 0) {
                    $running = false;
                }
                usleep(200000);
            }
        }
        $this->overWriteLine();
        $this->write($Task->task_message . " ✓\n");
        $this->showCursor();
    }

    /**
     * Write a line to cli
     *
     * @param string $message
     * @return void
     */
    protected function write(string $message) {
        print($message);
    }

    /**
     * Over write last printed line
     *
     * @return void
     */
    protected function overWriteLine() {
        $this->write("\x0D");
        $this->write("\x1B[2K");
    }

    /**
     * hide the cli cursor
     *
     * @return void
     */
    protected function hideCursor() {
        $this->write("\e[?25l");
    }

    /**
     * show the cli cursor
     *
     * @return void
     */
    protected function showCursor() {
        $this->write("\e[?25h");
    }
}