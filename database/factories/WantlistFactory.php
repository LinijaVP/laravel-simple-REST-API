<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wantlist>
 */
class WantlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(["W","B"]); // Want, Bought

        return [
            "customer_id" => Customer::factory(),
            "price" => $this->faker->numberBetween(50,2000),
            "item" => $this->faker->word(),
            "status" => $status,
            "bought_date" => $status == "B" ? $this->faker->dateTimeThisYear() : NULL
        ];
    }
}
