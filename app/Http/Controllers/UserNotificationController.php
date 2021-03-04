<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
	public function index()
	{
		return auth()->user()->unreadNotifications;
	}

    public function destroy(User $user, $notificationId)
    {
    	auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
