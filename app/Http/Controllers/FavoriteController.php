<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
	public function store(Reply $reply)
	{
		return $reply->favorite();
	}
}
