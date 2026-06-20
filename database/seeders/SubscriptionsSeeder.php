<?php

namespace Database\Seeders;

use App\Models\Subscriptions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 10 subscription random (campur aktif & expired)
        Subscriptions::factory(10)->create();

        // 5 yang pasti expired
        Subscriptions::factory(5)->expired()->create();

        // 5 yang pasti masih aktif
        Subscriptions::factory(5)->active()->create();
    }
}
