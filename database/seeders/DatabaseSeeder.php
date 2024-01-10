<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\ProductService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* \App\Models\Company::factory(5)->create()->each(function ($company) {
            $company->admins()->saveMany(\App\Models\Admin::factory(5)->make());
            $company->customers()->saveMany(\App\Models\Customer::factory(5)->make());

            $company->branches()->saveMany(\App\Models\Branch::factory(5)->make());
            $company->productServices()->saveMany(\App\Models\ProductService::factory(5)->make());
        }); */

         // Check if there are existing companies
         $existingCompanies = Company::count();

         if ($existingCompanies === 0) {
             // Create companies if none exist
             Company::factory(5)->create();
         }

        // Use the existing companies to create branches
        Company::all()->each(function ($company) {
            $company->admins()->saveMany(Admin::factory(5)->make());
            $company->customers()->saveMany(Customer::factory(5)->make());

            $company->branches()->saveMany(Branch::factory(5)->make());
            $company->productServices()->saveMany(ProductService::factory(5)->make());
        });

        /*
        \App\Models\User::factory(1)->create();
         */

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
