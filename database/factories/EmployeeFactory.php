<?php

/** @var Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'login' => $faker->userName,
        'name' => $faker->name,
    ];
});
