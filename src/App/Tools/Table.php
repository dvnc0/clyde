<?php
namespace Clyde\Tools;

/**
 * @phpstan-type THColumn array<string>
 * @phpstan-type TableRow array<mixed>
 */
class Table
{
	/**
	 * calculated column widths
	 *
	 * @var array
	 */
	protected array $column_widths = [];

	/**
	 * the output string for the table
	 *
	 * @var string
	 */
	protected string $output = '';

	/**
	 * Eventually this will have a print table helper method
	 *
	 * @param array{headers:THColumn, rows: TableRow} $table the table data	 
	 * @return string
	 */
	public function buildTable(array $table): string {
		if (empty($table['rows'])) {
			return '';
		}
		
		$this->processCellWidths($table['headers']);

		foreach ($table['rows'] as $row){
			$this->processCellWidths($row);
		}

		$this->buildHeaderRow($table['headers']);
		$this->buildDividerRow();

		foreach($table['rows'] as $row) {
			$this->buildDataRow($row);
		}

		return $this->output;
	}

	/**
	 * process the width of cells
	 *
	 * @param array<mixed> $cells the cell data
	 * @return void
	 */
	protected function processCellWidths(array $cells): void {
		foreach ($cells as $row_key => $value) {
			$length = strlen($value) + 2;
			if (empty($this->column_widths[$row_key]) || $this->column_widths[$row_key] < $length) {
				$this->column_widths[$row_key] = $length;
			}
		}
	}

	/**
	 * Builds the table header roq
	 *
	 * @param array<string> $headers the headers to build 
	 * @return void
	 */
	protected function buildHeaderRow(array $headers): void {
		foreach($headers as $row_key => $value) {
			$value         = str_pad($value, $this->column_widths[$row_key], ' ');
			$this->output .= '|' . $value;
			if (array_key_last($headers) === $row_key) {
				$this->output .=  '|';
			}
		}

		$this->output .= PHP_EOL;
	}

	/**
	 * Add the divider row after headers
	 *
	 * @return void
	 */
	protected function buildDividerRow(): void {
		foreach ($this->column_widths as $length) {
			$output_row    = '|';
			$row_cur       = str_pad($output_row, (int)$length + 1, '-');
			$this->output .= $row_cur;
		}
		$this->output .=  '|';
		$this->output .= PHP_EOL;
	}

	/**
	 * Builds a row of data for the table
	 *
	 * @param array $row the row data to build
	 * @return void
	 */
	protected function buildDataRow(array $row): void {
		foreach ($row as $row_key => $value) {
			$value         = str_pad($value, $this->column_widths[$row_key], ' ');
			$this->output .= '|' . $value;
			if (array_key_last($row) === $row_key) {
				$this->output .=  '|';
			}
		}
		$this->output .= PHP_EOL;
	}
}