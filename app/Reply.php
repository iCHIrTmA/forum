<?php

namespace App;

use App\Thread;
use App\Favorite;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	use Favorable, RecordsActivity;

	protected $guarded = [];
 	protected $with = ['owner', 'favorites'];
	
	public function owner()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function thread()
	{
		return $this->belongsTo(Thread::class);
	}
}
