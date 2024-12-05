<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->has(
                Customer::factory()
                ->count(2)
                ->hasWantlist(3)
            )
            ->create();

            User::factory()
            ->count(20)
            ->has(
                Customer::factory()
                ->count(1)
                ->hasWantlist(3)
            )
            ->create();

            User::factory()
            ->count(20)
            ->has(
                Customer::factory()
                ->count(3)
                ->hasWantlist(2)
            )
            ->create();
    }
}
