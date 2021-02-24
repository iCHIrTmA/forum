<?php

namespace App;

use App\Activity;
use App\Channel;
use App\Reply;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
	protected $guarded = [];
    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });        

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });

        static::created(function ($thread) {
            Activity::create([
                'user_id' => auth()->id(),
                'type' => 'created_thread',
                'subject_id' => $thread->id,
                'subject_type' => Thread::class,
            ]);
        });
    }
	
    public function path()
    {
    	return "/threads/{$this->channel->slug}/{$this->id}";	
    }

    public function replies()
    {
    	return $this->hasMany(Reply::class);
    }

    // public function getReplyCountAttribute()
    // {
    //     return $this->replies()->count();
    // }

    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
    	$this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
