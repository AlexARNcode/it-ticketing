<?php

namespace Database\Seeders;

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

        // 2. Users
        User::factory()->create([
            'name' => 'test',
            'email' => 'test@test.com',
            'organization_id' => $organization->id,
            'password' => Hash::make('password'),

        ]);

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
            ]);
        }
    }
}
