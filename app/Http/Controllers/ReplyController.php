<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function index($channelId, Thread $thread)
	{
		return $thread->replies()->paginate(20);
	}

	public function store($channelId, Thread $thread)
	{
		$this->validate(request(),[
			'body' => 'required',
		]);

		$reply = $thread->addReply([
			'body' => request('body'),
			'user_id' => auth()->id()
		]);

		return $reply->load('owner');

		// if (request()->expectsJson()) {
		// 	return $reply;
		// }

		return back()
			->with('flash', 'Your reply is submitted');
	}

	public function update(Reply $reply)
	{
		$this->authorize('update', $reply);
				
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
}
