<?php

namespace App\Inspections;

class Spam
{
	public function detect($body)
	{
		$this->detectInvalidKeywords($body);
		$this->detectKeyBeingHeldDown($body);

		return false;
	}

	public function detectInvalidKeywords($body)
	{
		$invalidKeywords = [
			'A spam',
		];

		foreach ($invalidKeywords as $keyword) {
			if (stripos($body, $keyword) !== false) {
				throw new \Exception('Your reply is spam');
			}
		}
	}

	public function detectKeyBeingHeldDown($body)
	{
		if (preg_match('/(.)\\1{4,}/', $body)) {
			throw new \Exception('Your reply is spam');
		}
	}
}
