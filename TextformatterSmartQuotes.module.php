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
			'custom' => 'Custom (define characters below)',
		];
		$field->defaultValue = 'german';
		$field->value = $data['quoteStyle'] ?? 'german';
		$inputfields->add($field);

		$field = wire('modules')->get('InputfieldText');
		$field->name = 'customOpen';
		$field->label = 'Custom opening quote';
		$field->description = 'Character(s) used as opening quote when style is set to Custom.';
		$field->value = $data['customOpen'] ?? '';
		$field->showIf = 'quoteStyle=custom';
		$field->requiredIf = 'quoteStyle=custom';
		$field->columnWidth = 50;
		$inputfields->add($field);

		$field = wire('modules')->get('InputfieldText');
		$field->name = 'customClose';
		$field->label = 'Custom closing quote';
		$field->description = 'Character(s) used as closing quote when style is set to Custom.';
		$field->value = $data['customClose'] ?? '';
		$field->showIf = 'quoteStyle=custom';
		$field->requiredIf = 'quoteStyle=custom';
		$field->columnWidth = 50;
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
			'custom' => ['open' => $this->customOpen, 'close' => $this->customClose],
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