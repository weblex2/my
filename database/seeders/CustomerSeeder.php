<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;
use App\Models\CustomerAddress;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('de_DE'); // Für deutsche PLZ

       
        #Log::info('Customer selected:', ['customer_id' => $customer->customer_id, 'customer' => $customer]);

        for ($i = 0; $i < 30; $i++) {
            Customer::create([
                'external_id' => fake()->uuid(),
                'name' => $faker->lastName,
                'first_name' => $faker->firstName,
                'is_active' => $faker->boolean,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'primary_address' => 0,
                'website' => fake()->url(),
                'comments' => fake()->paragraph(),
            ]);
        }
    }
}
