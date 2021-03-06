<?php

namespace App\Inspections;

class InvalidKeyWords
{
	public $keywords = [
		'A spam',
	];

	public function detect($body)
	{
		foreach ($this->keywords as $keyword) {
			if (stripos($body, $keyword) !== false) {
				throw new \Exception('Your reply is spam');
			}
		}
	}
}