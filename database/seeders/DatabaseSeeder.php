<?php

namespace Database\Seeders;

use App\Models\System\Tenant;
use App\Models\Tenant\Company;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     */
    public function run(): void {

        $clients = [
            ["id" => 1, "name" => "Suplos", "subdomain" => "suplos"],
            ["id" => 2, "name" => "DEVC", "subdomain" => "devc"]
        ];

        foreach($clients as $client) {

            $tenant = Tenant::create($client);

            $tenant->createDomain([
                "domain" => $client["subdomain"].".localhost",
            ]);

            // User::factory()->create();

        }

        /* php artisan migrate:reset
        php artisan migrate

        php artisan tinker
        $tenant1 = App\Models\Tenant\Tenant::create(['id' => 3]);
        $tenant1->domains()->create(['domain' => 'suplos.localhost']);

        $tenant2 = App\Models\Tenant\Tenant::create(['id' => 4]);
        $tenant2->domains()->create(['domain' => 'devc.localhost']);

        App\Models\Tenant::all()->runForEach(function () {
            App\Models\User::factory()->create();
        }); */

    }

}
