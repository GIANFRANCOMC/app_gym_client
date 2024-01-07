<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\ProductService;
use App\Http\Requests\Admins\ProductServices\{StoreProductServiceRequest, UpdateProductServiceRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductServiceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        $list = ProductService::when(Utilities::validateVariable($request->general), function($query) use ($request) {

                       $filter = "%".trim($request->general)."%";

                        $query->where("name", "like", $filter)
                              ->orwhere("description", "like", $filter);

                    })
                    ->orderBy("name", "ASC")
                    ->orderBy("description", "ASC")
                    ->paginate(10);

        return $list;

    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        return view("admins/productServices/main");

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

        $productService = new ProductService();

        DB::transaction(function() use($request, $productService) {

            $productService->name        = $request->name;
            $productService->description = $request->description;
            $productService->price       = $request->price;
            $productService->status      = $request->status;
            $productService->save();

        });

        return response()->json(["message" => "Producto - Servicio creado correctamente.", "productService" => $productService], 200);

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
