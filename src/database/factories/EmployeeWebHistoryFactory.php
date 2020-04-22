<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Sraban\OnlineVisitor\Models\Employee;
use Sraban\OnlineVisitor\Models\EmployeeWebHistory;
use Faker\Generator as Faker;

$factory->define(EmployeeWebHistory::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'ip_address' => function() {
        	return Employee::all()->random()->ip_address;
        }
    ];
});
