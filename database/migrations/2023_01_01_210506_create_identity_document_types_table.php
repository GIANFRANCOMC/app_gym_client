<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("identity_document_types", function (Blueprint $table) {
            $table->id();
            $table->string("name");

            $table->enum("status", ["active", "inactive"])->default("active");
            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
        });

        DB::table("identity_document_types")->insert([
            ["id" => 1, "name" => "Doc.trib.no.dom.sin.ruc"],
            ["id" => 2, "name" => "DNI"],
            ["id" => 3, "name" => "CE"],
            ["id" => 4, "name" => "RUC"],
            ["id" => 5, "name" => "Pasaporte"]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("identity_document_types");

    }

};
