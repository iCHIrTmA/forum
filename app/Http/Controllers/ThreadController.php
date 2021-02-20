<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilters;
use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
	public function index(Channel $channel, ThreadFilters $filters)
	{
		if ($channel->exists) {
			$threads = $channel->threads()->latest();
		} else {
			$threads = Thread::latest();
		}
		
		// if ($username = request('by')) {
		// 	$user = User::where('name', $username)->firstOrFail();

		// 	$threads->where('user_id', $user->id);
		// }

		$threads = $threads->filter($filters)->get();

		// $threads = Thread::filter($filters)->get();

		return view('threads.index', compact('threads'));
	}

	public function create()
	{
		return view('threads.create');
	}

	public function store(Request $request)
	{
		$this->validate($request,[
			'title' => 'required',
			'body' => 'required',
			'channel_id' => 'required|exists:channels,id',
		]);

		$thread = Thread::create([
			'user_id' => auth()->id(),
			'channel_id' => request('channel_id'), 
			'title' => request('title'),
			'body' => request('body')
		]);
		
		return redirect($thread->path());
	}

	public function show($channelId, Thread $thread)
	{
		return view('threads.show', compact('thread'));
	}
}
