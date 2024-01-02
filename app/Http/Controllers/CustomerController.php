<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $list = Customer::when(Utilities::validateVariable($request->general), function($query) use ($request) {

                       $filter = "%".trim($request->general)."%";

                        $query->where("last_name", "like", $filter)
                              ->orWhere("first_name", "like", $filter);

                    })
                    ->orderBy("last_name", "ASC")
                    ->orderBy("first_name", "ASC")
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admins/customers/index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
