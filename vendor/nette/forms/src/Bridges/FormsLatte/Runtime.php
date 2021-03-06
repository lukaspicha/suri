<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Bridges\FormsLatte;

use Nette;
use Nette\Utils\Html;
use Nette\Forms\Form;


/**
 * Runtime helpers for Latte.
 * @internal
 */
class Runtime
{
	use Nette\StaticClass;

	/**
	 * Renders form begin.
	 * @return string
	 */
	public static function renderFormBegin(Form $form, array $attrs, $withTags = TRUE)
	{
		$form->fireRenderEvents();
		foreach ($form->getControls() as $control) {
			$control->setOption('rendered', FALSE);
		}
		$el = $form->getElementPrototype();
		$el->action = (string) $el->action;
		$el = clone $el;
		if ($form->isMethod('get')) {
			$el->action = preg_replace('~\?[^#]*~', '', $el->action, 1);
		}
		$el->addAttributes($attrs);
		return $withTags ? $el->startTag() : $el->attributes();
	}


	/**
	 * Renders form end.
	 * @return string
	 */
	public static function renderFormEnd(Form $form, $withTags = TRUE)
	{
		$s = '';
		if ($form->isMethod('get')) {
			foreach (preg_split('#[;&]#', (string) parse_url($form->getElementPrototype()->action, PHP_URL_QUERY), -1, PREG_SPLIT_NO_EMPTY) as $param) {
				$parts = explode('=', $param, 2);
				$name = urldecode($parts[0]);
				if (!isset($form[$name])) {
					$s .= Html::el('input', ['type' => 'hidden', 'name' => $name, 'value' => urldecode($parts[1])]);
				}
			}
		}

		foreach ($form->getControls() as $control) {
			if ($control->getOption('type') === 'hidden' && !$control->getOption('rendered')) {
				$s .= $control->getControl();
			}
		}

		if (iterator_count($form->getComponents(TRUE, Nette\Forms\Controls\TextInput::class)) < 2) {
			$s .= "<!--[if IE]><input type=IEbug disabled style=\"display:none\"><![endif]-->\n";
		}

		return $s . ($withTags ? $form->getElementPrototype()->endTag() . "\n" : '');
	}

}
