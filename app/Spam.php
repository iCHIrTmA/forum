<?php

namespace App;

class Spam
{
	public function detect($body)
	{
		return $this->detectInvalidKeywords($body);
	}

	public function detectInvalidKeywords($body)
	{
		$invalidKeywords = [
			'A spam',
		];

		foreach ($invalidKeywords as $keyword) {
			if (stripos($body, $keyword) !== false) {
				throw new \Exception('Your reply is spam');
			} else {
				return false;
			}
		}
	}

}
