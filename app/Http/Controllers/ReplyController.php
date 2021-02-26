<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function store($channelId, Thread $thread)
	{
		$this->validate(request(),[
			'body' => 'required',
		]);

		$thread->addReply([
			'body' => request('body'),
			'user_id' => auth()->id()
		]);

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
