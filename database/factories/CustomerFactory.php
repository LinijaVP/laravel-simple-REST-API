<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(["S","G"]);
        $name = $this->faker->name();
        return [
            "name" => $name,
            "type"=> $type,
            "user_id" => User::factory(),
            "email" => null,
            "city" => $this->faker->city(),
            "country" => $this->faker->country(),
            "budget" => $this->faker->numberBetween(0,10000),
        ];
    }


    public function configure()
    { //link the users email to the customers email
        return $this->afterCreating(function ($customer) {
            $customer->email = $customer->user->email;
            $customer->save();
        });
    }
}
