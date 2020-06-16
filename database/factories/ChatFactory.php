<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use peertxt\models\Chat;
use Faker\Generator as Faker;
use peertxt\models\Campaign;
use peertxt\models\Company;
use peertxt\models\Contact;

$factory->define(Chat::class, function (Faker $faker) {
	return [
		'uuid' => $faker->uuid,
		'company_id' => rand(1, Company::count()),
		'campaign_id' => rand(1, Campaign::count()),
		'contact_id' => rand(1, Contact::count()),
	];
});
