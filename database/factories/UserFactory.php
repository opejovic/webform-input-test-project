<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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
        'phone_number' => $faker->tollFreePhoneNumber,
        'address' => $faker->streetAddress,
        'zip_code' => $faker->postcode,
        'photo_path' => "photos/exampleUserPhoto.jpeg",
        'license_document_path' => "photos/exampleLicenseDocument.pdf",
        'email_verified_at' => now()
    ];
});
