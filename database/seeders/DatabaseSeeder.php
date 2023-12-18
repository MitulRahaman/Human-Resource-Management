<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Degree;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([SuperUser::class, PermissionSeeder::class, AssetTypeSeeder::class, BankSeeder::class, Degree::class, InstituteSeeder::class,
            LeaveTypeSeeder::class, MenuSeeder::class, OrganizationSeeder::class]);

    }
}
