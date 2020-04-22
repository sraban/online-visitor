<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Sraban\OnlineVisitor\Models\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'emp_name' => $faker->name,
        'emp_id' => strtoupper($faker->unique()->domainWord.rand(1,1000)),
        'ip_address' => $faker->localIpv4
    ];
});
