<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
    	try {
	    	$user = User::where('confirmation_token', request('token'))
	    		->firstOrFail()
	    		->confirm();    		
    	} catch (\Exception $e) {
	    	return redirect(route('threads'))
	    		->with('flash', 'Invalid token');    		
    	}

    	return redirect(route('threads'))
    		->with('flash', 'Your account is now confirmed! You may post to the forum');
    }
}
