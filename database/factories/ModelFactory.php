<?php

use Illuminate\Support\Facades\Hash;

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(4, false),
        'owner' => $faker->company,
        'category' => $faker->company,
        'city' => $faker->address,
        'price' => rand(0, 300),
        'description'=>$faker->text,
        'image' => $faker->url,
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
	return [
		'name' => $faker->name,
		'email' => $faker->email,
		'password' => Hash::make('secret'),
        'avatar' => $faker->url
	];
});