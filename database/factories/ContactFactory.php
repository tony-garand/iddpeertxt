<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use peertxt\models\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
	return [
		'uuid' => $faker->uuid,
		'company_id' => 2,
		'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'phone' => $faker->phoneNumber,
		'email' => $faker->email,
		'address1' => $faker->address,
		'address2' => $faker->secondaryAddress,
		'city' => $faker->city,
		'state' => $faker->state,
		'zip' => $faker->postcode
	];
});
