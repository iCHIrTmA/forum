<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Rules\SpamFree;
use App\Thread;
use App\Trending;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
	public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
	{
		$threads = $this->getThreads($channel, $filters);

		if (request()->wantsJson()) {
			return $threads;
		}

		return view('threads.index', [
			'threads' => $threads,
			'trending' => $trending->get(),
		]);
	}

	public function create()
	{
		return view('threads.create');
	}

	public function store(Request $request)
	{
		$this->validateReply($request);

		$thread = Thread::create([
			'user_id' => auth()->id(),
			'channel_id' => request('channel_id'), 
			'title' => request('title'),
			'slug' => Str::slug(request('title')),
			'body' => request('body')
		]);
		
		return redirect($thread->path())
			->with('flash', 'Your thread has been published!');
	}

	public function show($channelId, Thread $thread, Trending $trending)
	{
		if (auth()->check()) {
			auth()->user()->read($thread);
		}

		$trending->push($thread);

		$thread->increment('visits');

		return view('threads.show', [
			'thread' => $thread,
		]);
	}

	public function destroy($channel, Thread $thread)
	{
		$this->authorize('update', $thread);
		// if ($thread->user_id != auth()->id()) {
		// 	abort(403);
		// }

		$thread->delete();
		
		return redirect('/threads');
	}

	public function getThreads(Channel $channel, ThreadFilters $filters)
	{
		$threads = Thread::latest()->filter($filters);

		if ($channel->exists) {
			$threads->where('channel_id', $channel->id);
		}

		// dd($threads->toSql());

		return $threads->paginate(25);		
	}

	public function validateReply(Request $request)
	{
		$this->validate($request,[
			'title' => ['required', new Spamfree],
			'body' => ['required', new Spamfree],
			'channel_id' => ['required', 'exists:channels,id'],
		]);

		// resolve(Spam::class)->detect(request('body'));		
	}
}