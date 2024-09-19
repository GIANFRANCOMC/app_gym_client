<?php

namespace Database\Seeders;

use App\Models\System\{Item, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemDatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        User::factory(2)->create();
        Item::factory(50)->create();

    }

}
