<?php

namespace Database\Seeders;

use App\Models\MembershipPlans;
use App\Models\User;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     UserSeeder::class,
        //     ArticleSeeder::class,
        //     SubscriptionsSeeder::class,
        // ]);


        MembershipPlans::create([
            "name" => "monthly",
            "price" => 5000,
            "duration_months" => 1,
            "is_active" => true,
        ]);
        
        MembershipPlans::create([
            "name" => "semiannual",
            "price" => 20000,
            "duration_months" => 6,
            "is_active" => true,
        ]);
    }
}
