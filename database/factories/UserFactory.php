<?php

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

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->word,
        'name' => $faker->unique()->name

    ];
});


$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'category_id' => App\Category::all()->random()->id,
        'code' => $faker->unique()->word,
        'name' => $faker->unique()->name
    ];
});
