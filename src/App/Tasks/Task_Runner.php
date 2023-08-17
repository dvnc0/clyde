<?php
namespace Clyde\Tasks;

use Clyde\Application;
use Clyde\Core\Event_Dispatcher;
use Clyde\Tasks\Task_Base;
use Clyde\Tools\Printer;

class Task_Runner {

	protected Printer $Printer;
	protected Application $Application;
	protected Event_Dispatcher $Event_Dispatcher;

	/**
	 * symbols to use for animation
	 *
	 * @var array
	 */
	protected array $symbols = [];

	public function __construct(Application $Application ,$symbols = []){
		$this->symbols = $symbols ?: mb_str_split('⢿⣻⣽⣾⣷⣯⣟⡿');
		$this->Application = $Application;
		$this->Printer = $this->Application->Printer;
		$this->Event_Dispatcher = $this->Application->Event_Dispatcher;
	}

	/**
	 * Execute the task with inline spinner
	 *
	 * @param Task_Base $Task
	 * @return void
	 */
	public function run(Task_Base $Task) {
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
		$this->Printer->success($Task->task_message . " ✓\n");
		$this->showCursor();
	}

	/**
	 * Write a line to cli
	 *
	 * @param string $message
	 * @return void
	 */
	protected function write(string $message) {
		$this->Printer->warning($message, false);
	}

	/**
	 * Over write last printed line
	 *
	 * @return void
	 */
	protected function overWriteLine() {
		print("\x0D");
		print("\x1B[2K");
	}

	/**
	 * hide the cli cursor
	 *
	 * @return void
	 */
	protected function hideCursor() {
		print("\e[?25l");
	}

	/**
	 * show the cli cursor
	 *
	 * @return void
	 */
	protected function showCursor() {
		print("\e[?25h");
	}
}