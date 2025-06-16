<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'umesh@optimaitsolution.com',
            'password' => bcrypt('admin'),
            'user_type_id' => UserType::SUPERADMIN
        ]);
    }
}
