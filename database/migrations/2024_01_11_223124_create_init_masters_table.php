<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        // Masters
        Schema::create("identity_document_types", function(Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
        });

        Schema::create("document_types", function(Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("name");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
        });

        Schema::create("currencies", function(Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("sign");
            $table->string("singular_name");
            $table->string("plural_name");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
        });

        Schema::create("companies", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("identity_document_type_id");
            $table->string("document_number");
            $table->string("legal_name");
            $table->string("commercial_name");
            $table->string("address")->nullable();
            $table->string("telephone")->nullable();
            $table->string("email")->nullable();
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("identity_document_type_id")->references("id")->on("identity_document_types")->onDelete("cascade");
        });

        Schema::create("users", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("identity_document_type_id");
            $table->string("document_number");
            $table->string("name");
            $table->string("email");
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->rememberToken();
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
            $table->foreign("identity_document_type_id")->references("id")->on("identity_document_types")->onDelete("cascade");
            $table->unique(["email", "company_id"]);
        });

        // Inserts
        DB::table("identity_document_types")->insert([
            ["id" => 1, "name" => "Doc.trib.no.dom.sin.ruc"],
            ["id" => 2, "name" => "DNI"],
            ["id" => 3, "name" => "CE"],
            ["id" => 4, "name" => "RUC"],
            ["id" => 5, "name" => "Pasaporte"]
        ]);

        DB::table("document_types")->insert([
            ["id" => 1, "code" => "BV", "name" => "BOLETA DE VENTA"],
            ["id" => 2, "code" => "FA", "name" => "FACTURA"]
        ]);

        DB::table("currencies")->insert([
            ["id" => 1, "code" => "PEN", "sign" => "S/", "singular_name" => "SOL", "plural_name" => "SOLES"]
        ]);

        DB::table("companies")->insert([
            ["id" => 1, "identity_document_type_id" => 1, "document_number" => "999999999", "legal_name" => "PAGAPE S.A.", "commercial_name" => "PAGAPE", "address" => "-", "telephone" => "-", "email" => "-"]
        ]);

        DB::table("users")->insert([
            ["company_id" => 1, "identity_document_type_id" => 1, "document_number" => "999999999", "name" => "Usuario demo", "email" => "demo@pagape.com", "password" => Hash::make("1")],
            ["company_id" => 1, "identity_document_type_id" => 2, "document_number" => "71883137", "name" => "Gianfranco", "email" => "gmc@pagape.com", "password" => Hash::make("1")]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("users");
        Schema::dropIfExists("companies");
        Schema::dropIfExists("currencies");
        Schema::dropIfExists("document_types");
        Schema::dropIfExists("identity_document_types");

    }

};
