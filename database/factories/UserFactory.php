<?php

use App\User;
use App\Reply;
use App\Thread;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

//$factory->define(Thread::class, function (Faker $faker) {
//    return [
//        'user_id' => function(){
//            return factory(User::class)->create()->id;
//        },
//        'title' => $faker->sentence,
//        'body' => $faker-> paragraph
//    ];
//});
//
//$factory->define(Reply::class, function (Faker $faker) {
//    return [
//        'user_id' => function() {
//            return factory(User::class)->create()->id;
//        },
//        'thread_id' => function() {
//            return factory(Thread::class)->create()->id;
//        },
//        'body' => $faker->paragraph
//    ];
//});
