<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Providers\ThreadReceivedNewReply;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
	public function index($channelId, Thread $thread)
	{
		return $thread->replies()->paginate(20);
	}

	public function store($channelId, Thread $thread, CreatePostRequest $form)
	{
		if ($thread->locked) {
			return response('This thread is locked', 422);
		}

		$reply = $thread->addReply([
			'body' => request('body'),
			'user_id' => auth()->id()
		]);

		return $reply->load('owner');
	}

	public function update(Reply $reply)
	{
		$this->authorize('update', $reply);

		$this->validate(request(), [
			'body' => ['required', new Spamfree],
		]);
				
		$reply->update(request(['body']));			
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
