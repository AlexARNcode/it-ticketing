<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Organization
        $organization = Organization::create([
            'name' => fake()->word()
        ]);

        // 2a. Admin user
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'organization_id' => $organization->id,
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN->value,

        ]);

        // 2b. Tech user
        User::factory()->create([
            'name' => 'tech',
            'email' => 'tech@test.com',
            'organization_id' => $organization->id,
            'password' => Hash::make('password'),
            'role' => UserRole::TECH->value,
        ]);

        // 2c. Normal users
        for ($i = 0; $i < 3; $i++) {
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();

            $first = Str::slug($firstName);
            $last = Str::slug($lastName);

            $email = Str::of($first)
                ->append('.')
                ->append(str_replace('-', '.', $last))
                ->append("@{$organization->name}.com")
                ->lower()
                ->toString();

            User::factory()->create([
                'name' => "$firstName $lastName",
                'email' => $email,
                'organization_id' => $organization->id,
                'password' => Hash::make('password'),
                'role' => UserRole::USER->value,
            ]);
        }
    }
}
