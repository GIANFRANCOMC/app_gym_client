<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerUser;
use App\Models\ProductService;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Company::factory(5)->create()->each(function($company) {

            $admins = Admin::factory(2)->create(['company_id' => $company->id]);
            $customers = Customer::factory(2)->create(['company_id' => $company->id]);

            $admins->each(function($admin) use($company) {

                User::factory(1)->create(['company_id' => $company->id, 'admin_id' => $admin->id]);

            });

            $customers->each(function($customer) use($company) {

                CustomerUser::factory(1)->create(['company_id' => $company->id, 'customer_id' => $customer->id]);

            });
        });
    }
}
