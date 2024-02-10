<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
            'joining_date' => '2023-07-15'
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Vikas',
            'email' => 'user@user.com',
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
            'joining_date' => '2024-02-09'
        ]);

        $this->call(LeaveTypeSeeder::class);
    }
}
