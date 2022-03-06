<?php

use Faker\Generator as Faker;
use App\Models\CustomField;


$factory->define(CustomField::class, function (Faker $faker) {
    return [
        'field_type' => $faker->word,
        'options' => 'a:0:{}',
        'label' => $faker->word,
        'table_name' => $faker->sentence(1),
    ];
});
