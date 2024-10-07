<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("companies", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("identity_document_type_id");
            $table->string("document_number");
            $table->string("legal_name");
            $table->string("commercial_name");

            $table->enum("status", ["active", "inactive"])->default("active");
            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("identity_document_type_id")->references("id")->on("identity_document_types")->onDelete("cascade");
        });

        DB::table("companies")->insert([
            "id" => 1,
            "identity_document_type_id" => 1,
            "document_number" => "999999999",
            "legal_name" => "PAGAPE S.A.",
            "commercial_name" => "PAGAPE"
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("companies");

    }

};
