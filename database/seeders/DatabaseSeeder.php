<?php

namespace Database\Seeders;

use App\Models\System\{Company, Customer, Item, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     */
    public function run(): void {

        Company::factory(1)->create();
        User::factory(2)->create();
        Item::factory(50)->create();
        Customer::factory(10)->create();

    }

}
