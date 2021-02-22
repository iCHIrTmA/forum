<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
	public function store(Reply $reply)
	{
		$reply->favorite();
		// Favorite::create([
		// 	'user_id' => auth()->id(),
		// 	'favorited_id' => $reply->id,
		// 	'favorited_type' => get_class($reply),
		// ]);
	}
}
