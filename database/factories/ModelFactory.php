<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\UserStatement::class, function (Faker\Generator $faker) {
    return [
        'amount' => 0,
        'currency' => $faker->currencyCode,
        'user_id' => mt_rand(1, 10),
        'balance' =>0,
        'bonus_balance' =>0,
        'bonus_status' =>0,
        'deposits' =>0,
        'withdrawals' =>0,
    ];
});

$factory->define(App\Transaction::class, function (Faker\Generator $faker) {
    return [
        'amount' => $faker->randomFloat(2,0,200),
        'user_id' => mt_rand(1, 10),
        'user_statement_id' => mt_rand(1, 10),
        'transaction_type' => $faker->randomElement(['D','C'],1),
        'bonus' => $faker->randomFloat(2,0,30),
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {

    $hasher = app()->make('hash');
    
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->email,
        'password' => $hasher->make("secret"),
        'is_admin' => mt_rand(0, 1),
        'gender' => 'M',
        'country' => $faker->countryCode,
        'bonus' => mt_rand(0,30),

    ];
});