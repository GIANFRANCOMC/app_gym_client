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
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Company::factory(1)->create(["commercial_name" => "SUPLOS", 'status' => 'active']);
        Company::factory(1)->create(["commercial_name" => "INTEL", 'status' => 'active']);
        Company::factory(1)->create(["commercial_name" => "DEVC TECNOLOGIAS", 'status' => 'active']);

        (Company::all())->each(function($company) {

            $admins = Admin::factory(2)->create(['company_id' => $company->id, 'status' => 'active']);
            $customers = Customer::factory(2)->create(['company_id' => $company->id]);

            $admins->each(function($admin) use($company) {

                User::factory(1)->create(['email' => Str::lower(str_replace(" ", "_", $admin->first_name))."@".Str::lower(str_replace(" ", "_", $company->commercial_name)).".com", 'company_id' => $company->id, 'admin_id' => $admin->id, 'status' => 'active']);

            });

            $customers->each(function($customer) use($company) {

                CustomerUser::factory(1)->create(['company_id' => $company->id, 'customer_id' => $customer->id]);

            });
        });
    }
}
