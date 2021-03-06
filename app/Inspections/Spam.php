<?php

namespace App\Inspections;

// use App\Inspections\InvalidKeywords;

class Spam
{
	protected $inspections = [
		InvalidKeywords::class,
		KeyBeingHeldDown::class,
	];

	public function detect($body)
	{
		foreach($this->inspections as $inspection) {
			app($inspection)->detect($body);
		}
		
		return false;
	}
}
