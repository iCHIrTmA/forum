<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
	protected $request, $builder;
	protected $filters = [];

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function apply($builder)
	{
		$this->builder = $builder;

		// dd($this->getFilters());

		foreach ($this->getFilters() as $filter => $value) {
			if (method_exists($this, $filter)) {
				$this->$filter($value);
			} 

			// if ( ! $this->hasFilter($filter)) return;
				
			// $this->$filter($this->request->$filter);
		}

		return $this->builder;
	}

	public function getFilters()
	{
		return $this->request->only($this->filters); 
	}

	// public function hasFilter($filter): bool
	// {
	// 	return $this->request->has($filter);
	// }
}
