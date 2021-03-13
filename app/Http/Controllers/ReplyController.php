<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
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
		return $thread->addReply([
			'body' => request('body'),
			'user_id' => auth()->id()
		])->load('owner');
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
