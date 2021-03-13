<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Rules\SpamFree;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function index($channelId, Thread $thread)
	{
		return $thread->replies()->paginate(20);
	}

	public function store($channelId, Thread $thread)
	{
		try {
			request()->validate([
				'body' => ['required', new Spamfree],
			]); // laravel 5.5 +
			// $this->validate(request(), [
			// 	'body' => ['required', new Spamfree],
			// ]);

			$reply = $thread->addReply([
				'body' => request('body'),
				'user_id' => auth()->id()
			]);
		} catch (\Exception $e){
			return response('Sorry your reply could not be saved', 422);
		}


		// if (request()->expectsJson()) {
		// 	return $reply;
		}
		return $reply->load('owner');

		// return back()
		// 	->with('flash', 'Your reply is submitted');
	}

	public function update(Reply $reply)
	{
		$this->authorize('update', $reply);

		try {
			$this->validate(request(), [
				'body' => ['required', new Spamfree],
			]);
				
			$reply->update(request(['body']));			
		} catch (\Exception $e) {
			return response('Sorry your reply could not be saved', 422);			
		}
	}

	public function destroy(Reply $reply)
	{
		$this->authorize('update', $reply);

		$reply->delete();

		if (request()->expectsJson()) {
			return response(['status' => 'Reply deleted']);
		}

		return back();
	}

	// public function validateReply()
	// {
	// 	$this->validate(request(),[
	// 		'body' => 'required|spamfree',
	// 	]);

	// 	// resolve(Spam::class)->detect(request('body'));
	// }
}
