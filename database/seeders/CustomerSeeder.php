<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('de_DE'); // Für deutsche PLZ


        for ($i = 0; $i < 30; $i++) {
            Customer::create([
                'customer_id' => fake()->uuid(),
                'name' => $faker->name,
                'active' => $faker->boolean,
                'address' => $faker->address,
                'address_2' => rand(0, 1) ? fake()->secondaryAddress() : null,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip' => fake()->postcode(),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'website' => fake()->url(),
                'comments' => fake()->paragraph(),
                
            ]);
        }
    }
}
