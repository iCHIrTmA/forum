<?php

namespace App;

use App\Favorite;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $guarded = [];
	
	public function owner()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function favorites()
	{
		return $this->morphMany(Favorite::class, 'favorited');
	}

	public function favorite()
	{		
		return $this->favorites()->create(['user_id' => auth()->id()]);
	}

}
