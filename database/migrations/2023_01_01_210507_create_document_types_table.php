<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("document_types", function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("name");

            $table->enum("status", ["active", "inactive"])->default("active");
            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
        });

        DB::table("document_types")->insert([
            ["id" => 1, "code" => "BE", "name" => "BOLETA DE VENTA"],
            ["id" => 2, "code" => "FA", "name" => "FACTURA"],
            ["id" => 3, "code" => "NV", "name" => "NOTA DE VENTA"]
        ]);
    }

    public function down(): void {

        Schema::dropIfExists("document_types");

    }

};
