<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;
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
			$this->validateReply();

			$reply = $thread->addReply([
				'body' => request('body'),
				'user_id' => auth()->id()
			]);
		} catch (\Exception $e){
			return response('Sorry your reply could not be saved', 422);
		}

		return $reply->load('owner');

		// if (request()->expectsJson()) {
		// 	return $reply;
		// }

		// return back()
		// 	->with('flash', 'Your reply is submitted');
	}

	public function update(Reply $reply)
	{
		$this->authorize('update', $reply);

		try {
			$this->validateReply();
				
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

	public function validateReply()
	{
		$this->validate(request(),[
			'body' => 'required',
		]);

		resolve(Spam::class)->detect(request('body'));
	}
}
