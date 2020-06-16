<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use peertxt\models\ChatThread;
use Faker\Generator as Faker;

$factory->define(ChatThread::class, function (Faker $faker) {
	return [
		'chat_id' => rand(1, Chat::count()),
		'direction' => 1,
		'status' => 0,
		'subject' => $faker->realText(20),
		'private_notes' => $faker->realText()
	];
});
