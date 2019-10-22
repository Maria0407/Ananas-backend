<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function () {
    return [
        'content' => str_random(50),
        'author' => auth()->user()->name,
        'created_at' => now(),
        'user_id' => auth()->id()
    ];
});
