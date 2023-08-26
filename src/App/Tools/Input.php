<?php
namespace Clyde\Tools;

use Clyde\Tools\Printer;

class Input
{
	/**
	 * Printer
	 *
	 * @var Printer
	 */
	protected Printer $Printer;

	/**
	 * construct
	 *
	 * @param Printer $Printer Printer
	 */
	public function __construct(Printer $Printer) {
		$this->Printer = $Printer;
	}

	/**
	 * get a response from cli
	 *
	 * @param string $message message to print
	 * @return string|boolean
	 */
	public function get(string $message): string|bool {
		$this->Printer->message($message);
		return readline();
	}

	/**
	 * Affirm prompt
	 *
	 * @param string $message message to print
	 * @return boolean
	 */
	public function affirm(string $message): bool {
		$this->Printer->message($message);
		$response = readline();

		preg_match("/^(y|Y|yes|YES|Yes|True|true)\b/m", $response, $match);

		return !empty($match);
	}

	/**
	 * Warn prompt, warns if answer not allowed
	 *
	 * @param string $message  message to print
	 * @param array  $required required answers
	 * @return boolean
	 */
	public function warnAffirm(string $message, array $required = []): bool {
		$this->Printer->warning($message);
		$response = readline();

		if (!empty($required)) {
			if (!in_array($response, $required)) {
				$second_try = $this->get('Please enter one of the following answers: ' . implode('/', $required));
				if (!in_array($second_try, $required)) {
					$this->Printer->error('Your answer could not be processed. Exiting now.');
					die;
				}
			}
		}

		preg_match("/^(y|Y|yes|YES|Yes|True|true)\b/m", $response, $match);

		return !empty($match);
	}

	/**
	 * Multiple choice prompt
	 *
	 * @param string $message message to print
	 * @param array  $options options
	 * @param string $default default answer
	 * @return mixed
	 */
	public function prompt(string $message, array $options, string $default) {
		$lastLetter    =  chr(ord('a')+count($options)-1);
		$selectOptions = range('a', $lastLetter);
		$options       = array_combine($selectOptions, $options);

		while(TRUE) {
			$this->Printer->warning($message);
			foreach ($options as $key => $option) {
				$this->Printer->message("$key - $option");
			}

			readline_callback_handler_install('Pick an option... '.PHP_EOL, function() {});
			$keystroke = stream_get_contents(STDIN, 1);

			if (in_array($keystroke, array_keys($options))) {
				return $options[$keystroke];
			}
	
			if (ord($keystroke) == 10) {
				return $default;
			}
	
			print PHP_EOL;

		}
	}

	/**
	 * Multiple choice prompt
	 *
	 * @param string  $message            message to print
	 * @param array   $options            options
	 * @param array   $default            default value
	 * @param boolean $single_answer_only multiselect ?
	 * @return array
	 */
	//phpcs:disable
	public function multipleChoice(string $message, array $options, $default = [], $single_answer_only = FALSE): array {
		$up_del = "\033[1A\033[2K";
	
		$this->Printer->warning($message);
	
		$count_rows   = count($options);
		$index        = 0;
		$default_keys = [];

		if (!empty($default)) {
			foreach ($default as $def) {
				$key = array_search($def, $options);
				if ($key === FALSE) {
					continue;
				}

				$default_keys[] = $key;
			}
		}

		$selected = $default_keys;
	
		$stdin = fopen('php://stdin', 'r');
	
		$this->printOptions($options, $index, $selected, $single_answer_only);
	
		while(TRUE) {
			stream_set_blocking($stdin, FALSE);
			$keystroke = fgets($stdin);
			system('stty cbreak -echo');
			
			if ($keystroke !== FALSE) {
				switch ($keystroke) {
					case "\033[A": // up
						$index = $index === 0 ? ($count_rows - 1) : $index - 1;
						break;
					case "\033[B": // down
						$index = $index === ($count_rows - 1) ? 0 : $index + 1;
						break;
					case "\n": // enter
						$out = [];
						if ($single_answer_only) {
							$out[] = $options[$index];
							fclose($stdin);
							system('stty sane');
							return $out;
						}
						foreach ($selected as $key) {
							$out[] = $options[$key];
						}
						fclose($stdin);
						system('stty sane');
						return $out;
					case " ": // space
						if ($single_answer_only) {
							$out = [];
							$out[] = $options[$index];
							fclose($stdin);
							system('stty sane');
							return $out;
						}
						if (in_array($index, $selected)) {
							$selected = array_filter($selected, function ($in) use ($index) {
								if ($in === $index) {
									return FALSE;
								}
	
								return TRUE;
							});
						} else {
							array_push($selected, $index);
						}
						break;
				}
	
				for($i = 0; $i <= $count_rows; $i++) {
					print($up_del);
				}
				$this->Printer->warning($message);
				$this->printOptions($options, $index, $selected, $single_answer_only);
			}
		}
	}
	//phpcs:enable

	/**
	 * Select one from list
	 *
	 * @param string $message message to print
	 * @param array  $options the options to choose from
	 * @return array
	 */
	public function list(string $message, array $options): array {
		return $this->multipleChoice($message, $options, [], TRUE);
	}

	/**
	 * Prints the options
	 *
	 * @param array   $options     options array
	 * @param integer $index       index
	 * @param array   $selected    what is selected
	 * @param boolean $single_only single answer
	 * @return void
	 */
	protected function printOptions(array $options, int $index, array $selected, $single_only = FALSE) {
		foreach($options as $key => $opt) {
			if ($key === $index) {
				$line = ">> ";
				if (in_array($key, $selected)) {
					$line = $single_only ? $line : $line . "[x]";
					$this->Printer->highlight($this->Printer->fullWidth("$line $opt"));
				} else {
					$line = $single_only ? $line : $line . "[ ]";
					$this->Printer->highlight($this->Printer->fullWidth("$line $opt"));
				}
				continue;
			}
	
			if (in_array($key, $selected)) {
				$line = $single_only ? "   " : "   [x]";
				$this->Printer->message("$line $opt");
			} else {
				$line = $single_only ? "   " : "   [ ]";
				$this->Printer->message("$line $opt");
			}
		}
	}

	/**
	 * password prompt
	 *
	 * @param string $message the message to print
	 * @return string
	 */
	public function password(string $message): string {
		$del_line = "\033[1K\r";
		$this->Printer->warning($message, FALSE);
		$password_buffer = [];
		$stdin           = fopen('php://stdin', 'r');
		while(TRUE) {
			stream_set_blocking($stdin, FALSE);
			$keystroke = fgets($stdin);
			system('stty cbreak -echo');
			if ($keystroke !== FALSE) {
				switch ($keystroke) {
					case "\n":
						fclose($stdin);
						system('stty sane');
						   return implode('', $password_buffer);
					case "\010":
					case "\177":
						print($del_line);
						$this->Printer->warning($message, FALSE);
						unset($password_buffer[array_key_last($password_buffer)]);
						$this->Printer->message(implode('', array_fill(0, count($password_buffer), '*')), FALSE);
						   break;
					default:
						$keys = str_split($keystroke);
						print($del_line);
						$this->Printer->warning($message, FALSE);
						$password_buffer = array_merge($password_buffer, $keys);
						$count           = count($password_buffer);
						$this->Printer->message(implode('', array_fill(0, $count, '*')), FALSE);
						   break;
				}
			}
		}
	}

	/**
	 * tabbed autocomplete
	 *
	 * @param string $message message to print
	 * @param array  $answers possible answers
	 * @return string
	 */
	public function autocompleteAnswers(string $message, array $answers): string {
		$callback = function ($line, $index) use ($answers) {
			return $answers;
		};

		readline_completion_function($callback);

		$this->Printer->warning($message);
		return readline();
	}

	/**
	 * single choice
	 *
	 * @param string $message message
	 * @param array  $options options
	 * @return string
	 */
	public function singleChoice(string $message, array $options): string {
		while (TRUE) {
			$prompt = $message . implode(', ', $options);
			$this->Printer->warning($prompt);
			$stdin     = fopen('php://stdin', 'r');
			$keystroke = trim(fgets($stdin));
			if (in_array((string) $keystroke, $options) === TRUE) {
				fclose($stdin);
				return $keystroke;
			}

			print PHP_EOL;
		}
	}

	/**
	 * Expnd prompt type
	 *
	 * @param string $message     message
	 * @param array  $list        list
	 * @param array  $sub_options options
	 * @return array
	 */
	public function expand(string $message, array $list, array $sub_options): array {
		//
		return [];
	}
}