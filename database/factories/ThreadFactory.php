<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Thread;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Thread::class, function (Faker $faker) {
	$title = $faker->sentence;

    return [
        'user_id' => factory(User::class),
        'channel_id' => factory(Channel::class),
        'title'   => $title,
        'slug'   => Str::slug($title),
        'body'    => $faker->paragraph,
        'visits' => 0,
        'locked' => false,
    ];
});
