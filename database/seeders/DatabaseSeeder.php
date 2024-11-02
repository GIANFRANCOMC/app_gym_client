<?php

namespace Database\Seeders;

use App\Models\System\{Company, Customer, Item, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     */
    public function run(): void {

        Item::factory(50)->create();

    }

}
