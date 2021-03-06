<?php

namespace App;

use App\Activity;

trait RecordsActivity
{
	protected static function bootRecordsActivity()
	{
		if (auth()->guest()) return;
		
		foreach (static::getActivitiesToRecord() as $event) {
	        static::$event(function ($model) use ($event) {
	            $model->recordActivity($event);
	        });			
		}

        static::deleting(function ($model) {
            $model->activity()->delete();
        });		
	}

	protected static function getActivitiesToRecord()
	{
		return ['created'];	
	} 

	public function recordActivity($event)
    {
    	$this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),    		
    	]);
        // Activity::create([
        //     'subject_id' => $this->id,
        //     'subject_type' => get_class($this),
        // ]);
    }

    public function activity()
    {
    	return $this->morphMany(Activity::class, 'subject');
    }

    public function getActivityType($event)
    {
    	$type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}"; 
    }

}