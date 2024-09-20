<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("companies", function (Blueprint $table) {
            $table->id();
            $table->enum("document_type", ["dni", "ruc", "none"])->default("none");
            $table->string("document_number")->nullable();
            $table->string("legal_name")->nullable();
            $table->string("commercial_name")->nullable();

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("companies");

    }

};
