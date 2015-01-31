<?php

namespace Nijens\Utilities\Configuration;

use JsonSerializable;
use SimpleXMLElement;

/**
 * JSONSerializableXMLElement
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Configuration
 **/
class JSONSerializableXMLElement extends SimpleXMLElement implements JsonSerializable {

	/**
	 * jsonSerialize
	 *
	 * Returns the data to be serialized to JSON
	 *
	 * @access public
	 * @return array|string|null
	 **/
	public function jsonSerialize() {
		$data = array_merge(array(), iterator_to_array($this->attributes() ) );

		foreach ($this as $key => $element) {
			if (isset($data[$key])) {
				if (is_array($data[$key]) === false) {
					$data[$key] = [$data[$key] ];
				}
				$data[$key][] = $element;
			}
			else {
				$data[$key] = $element;
			}
		}

		$textContent = trim($this);
		if (strlen($textContent) > 0) {
			if (empty($data) === false) {
				$data['#value'] = $textContent;
			}
			else {
				$data = $textContent;
			}
		}

		if (empty($data) ) {
			$data = null;
		}

		return $data;
	}
}
