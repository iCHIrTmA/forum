<?php

namespace App;

use App\Activity;
use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\RecordsActivity;
use App\Reply;
use App\ThreadSubscription;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

	protected $guarded = [];
    protected $with = ['creator', 'channel'];
    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });        

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
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
    	$reply = $this->replies()->create($reply);

        foreach($this->subscriptions as $subscription) {
            $subscription->user->notify(new ThreadWasUpdated($this, $reply));
        }

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
