<?php
namespace Clyde\Tools;

/**
 * @phpstan-type THColumn array<string>
 * @phpstan-type TableRow array<mixed>
 */
class Table
{
	/**
	 * Eventually this will have a print table helper method
	 *
	 * @param array{headers:THColumn, rows: TableRow} $table the table data	 
	 * @return string
	 */
	public function printTable(array $table): string {
		$column_lengths = [];

		foreach ($table['headers'] as $row_key => $value) {
			$length = strlen($value) + 2;
			if (empty($column_lengths[$row_key]) || $column_lengths[$row_key] < $length) {
				$column_lengths[$row_key] = $length;
			}
		}
		foreach ($table['rows'] as $key => $row){
			foreach($row as $row_key => $value) {
				$length = strlen($value) + 2;
				if (empty($column_lengths[$row_key]) || $column_lengths[$row_key] < $length) {
					$column_lengths[$row_key] = $length;
				}
			}
		}
		$output = '';
		foreach($table['headers'] as $row_key => $value) {
			$value = str_pad($value, $column_lengths[$row_key], ' ');
			$output .= '|' . $value;
			if (array_key_last($table['headers']) === $row_key) {
				$output .=  '|';
			}
		}
		$output .= PHP_EOL;
		foreach ($column_lengths as $key => $length) {
			$output_row = '|';
			$row_cur = str_pad($output_row, (int)$length + 1, '-');
			$output .= $row_cur;
		}
		$output .=  '|';
		$output .= PHP_EOL;
		foreach($table['rows'] as $key => $row) {
			foreach ($row as $row_key => $value) {
				$value = str_pad($value, $column_lengths[$row_key], ' ');
				$output .= '|' . $value;
				if (array_key_last($row) === $row_key) {
					$output .=  '|';
				}
			}
			$output .= PHP_EOL;
		}

		return $output;
	}
}