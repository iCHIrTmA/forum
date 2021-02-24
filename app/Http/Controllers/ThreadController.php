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
		$threads = $this->getThreads($channel, $filters);

		if (request()->wantsJson()) {
			return $threads;
		}

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
		// return $thread->replies;
		return view('threads.show', [
			'thread' => $thread,
			'replies' => $thread->replies()->paginate(1),
		]);
	}

	public function getThreads(Channel $channel, ThreadFilters $filters)
	{
		$threads = Thread::with('channel')->latest()->filter($filters);

		if ($channel->exists) {
			$threads->where('channel_id', $channel->id);
		}

		// dd($threads->toSql());

		return $threads->get();		
	}
}