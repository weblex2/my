<?php

namespace Database\Factories;

use App\Models\CustomerAddress;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Log;
use Faker\Factory as Faker;

class CustomerAddressFactory extends Factory
{
    protected $model = CustomerAddress::class;

    public function definition()
    {

        $faker = Faker::create('de_DE');
        $customer = Customer::all()->random();
        #Log::info('Customer selected:', ['customer_id' => $customer->customer_id, 'customer' => $customer]);

        return [
            'customer_id' => $customer->id, // Hier wird ein zufälliger Kunde erstellt
            'type' => $this->faker->randomElement(['invc', 'home']), // Zufälliger Typ 'invc' oder 'home'
            'address' => $faker->streetAddress, // Zufällige Adresse
            'address2' => rand(0, 1) ? fake()->secondaryAddress() : null, // Zufällige zweite Adresse
            'zip' => $faker->postcode, // Zufällige Postleitzahl
            'state' => $faker->state, // Zufälliger Bundesstaat
            'country' => $faker->country, // Zufälliges Land
        ];
    }
}
