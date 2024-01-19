<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

use App\Http\Requests\Admins\Customers\{StoreCustomerRequest, UpdateCustomerRequest};
use App\Models\{Customer, CustomerUser};

class CustomerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $userAuth = Auth::user();

        $list = Customer::when(Utilities::validateVariable($request->general), function($query) use($request) {

                        $filter = "%".trim($request->general)."%";

                        $query->where("number_document", "like", $filter)
                              ->orwhere("last_name", "like", $filter)
                              ->orWhere("first_name", "like", $filter);

                    })
                    ->where("company_id", $userAuth->company_id)
                    ->orderBy("last_name", "ASC")
                    ->orderBy("first_name", "ASC")
                    ->with(["customerUser"])
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("admins/customers/main");

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request) {

        $customer = new Customer();

        DB::transaction(function() use($request, $customer) {

            $userAuth = Auth::user();

            $customer->type_document   = $request->type_document;
            $customer->number_document = $request->number_document;
            $customer->last_name       = $request->last_name;
            $customer->first_name      = $request->first_name;
            $customer->birth_date      = $request->birth_date;
            $customer->gender          = $request->gender;
            $customer->phone           = $request->phone;
            $customer->company_id      = $userAuth->company_id;
            $customer->status          = $request->status;
            $customer->save();

            $customerUser = new CustomerUser();
            $customerUser->name        = $customer->last_name." ".$customer->first_name;
            $customerUser->email       = $request->email;
            $customerUser->password    = $request->number_document; //$request->password;
            $customerUser->company_id  = $customer->company_id;
            $customerUser->customer_id = $customer->id;
            $customerUser->status      = "active";
            $customerUser->save();

        });

        return response()->json(["message" => "Cliente creado correctamente.", "customer" => $customer], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer) {

        //

    }

}
