<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\ProductService;
use App\Http\Requests\Admins\ProductServices\{StoreProductServiceRequest, UpdateProductServiceRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        //

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("admins/home/main");

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
    public function store(StoreProductServiceRequest $request) {

        //

    }

    /**
     * Display the specified resource.
     */
    public function show(ProductService $productService) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductService $productService) {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductServiceRequest $request, ProductService $productService) {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductService $productService) {

        //

    }

}
