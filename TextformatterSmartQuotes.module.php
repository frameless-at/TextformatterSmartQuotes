<?php

/**
 * ProcessWire TextformatterSmartQuotes
 *
 * Replaces straight double quotes "..." with typographic quotes („…“, “…” or «…»),
 * but only in visible text content, leaving HTML tags and attributes untouched.
 *
 * @author frameless Media
 */

class TextformatterSmartQuotes extends Textformatter implements ConfigurableModule {

	public static function getModuleInfo() {
		return [
			'title' => 'Textformatter Smart Quotes',
			'version' => 1,
			'summary' => 'Replaces straight quotes "..." with typographic quotes („...“, “...”, or «...»), in visible text only.',
			'author' => 'frameless Media',
			'autoload' => false,
			'singular' => true,
			'icon' => 'quote-right',
		];
	}

	public function getModuleConfigInputfields(array $data) {
		$inputfields = new InputfieldWrapper();

		$field = wire('modules')->get('InputfieldSelect');
		$field->name = 'quoteStyle';
		$field->label = 'Quote style';
		$field->options = [
			'german' => 'German: „...”',
			'english' => 'English: “...”',
			'french' => 'French: « ... »',
		];
		$field->defaultValue = 'german';
		$field->value = $data['quoteStyle'] ?? 'german';

		$inputfields->add($field);
		return $inputfields;
	}

	public function formatValue(Page $page, Field $field, &$value) {
		$style = $this->quoteStyle ?? 'german';

		// Define opening and closing quote chars based on selected style
		$quotes = [
			'german' => ['open' => '„', 'close' => '“'],
			'english' => ['open' => '“', 'close' => '”'],
			'french' => ['open' => '« ', 'close' => ' »'], // French uses non-breaking spaces
		];
		$open = $quotes[$style]['open'] ?? '„';
		$close = $quotes[$style]['close'] ?? '“';

		// If no HTML, do simple replacement
		if (strpos($value, '<') === false) {
			$value = preg_replace('/"([^"]+)"/', $open . '$1' . $close, $value);
			return;
		}

		// Replace quotes only in text, not HTML
		$value = preg_replace_callback(
			'/(<[^>]+>)|([^<]+)/',
			function($matches) use ($open, $close) {
				if (!empty($matches[2])) {
					return preg_replace('/"([^"]+)"/', $open . '$1' . $close, $matches[2]);
				}
				return $matches[1];
			},
			$value
		);
	}
}