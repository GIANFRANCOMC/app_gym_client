<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("company_socials_media", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->enum("type", ["web", "facebook", "instagram", "tiktok", "whatsapp", "other"])->default("other");
            $table->text("link");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
        });

        Schema::create("branches", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->string("name");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
        });

        Schema::create("series", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("branch_id");
            $table->unsignedBigInteger("document_type_id");
            $table->string("code");
            $table->integer("number");
            $table->integer("init")->default(1);
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
            $table->foreign("document_type_id")->references("id")->on("document_types")->onDelete("cascade");
        });

        Schema::create("items", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->string("internal_code");
            $table->string("name");
            $table->text("description")->nullable();
            $table->decimal("price", 10, 2);
            $table->unsignedBigInteger("currency_id");
            $table->enum("type", ["product", "service", "subscription"])->default("product");
            $table->enum("duration_type", ["hour", "day", "today", "month", "year"])->nullable();
            $table->integer("duration_value")->nullable();
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
            $table->foreign("currency_id")->references("id")->on("currencies")->onDelete("cascade");
        });

        Schema::create("customers", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("identity_document_type_id");
            $table->string("document_number");
            $table->string("name");
            $table->string("email")->nullable();
            $table->string("phone_number")->nullable();
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
            $table->foreign("identity_document_type_id")->references("id")->on("identity_document_types")->onDelete("cascade");
        });

        Schema::create("warehouses", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("branch_id");
            $table->string("name");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
        });

        Schema::create("warehouse_items", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("warehouse_id");
            $table->unsignedBigInteger("item_id");
            $table->decimal("quantity", 10, 2)->default(0);
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("warehouse_id")->references("id")->on("warehouses")->onDelete("cascade");
            $table->foreign("item_id")->references("id")->on("items")->onDelete("cascade");
        });

        // Inserts
        DB::table("company_socials_media")->insert([
            ["company_id" => 1, "type" => "facebook", "link" => "https://www.facebook.com/GianfrancoMC"],
            ["company_id" => 1, "type" => "instagram", "link" => "https://www.instagram.com/gianfrancomc"],
            ["company_id" => 1, "type" => "whatsapp", "link" => "https://wa.me/987057624"]
        ]);

        DB::table("branches")->insert([
            ["id" => 1, "company_id" => 1, "name" => "Sede Principal"]
        ]);

        DB::table("series")->insert([
            ["branch_id" => 1, "document_type_id" => 1, "code" => "BV", "number" => 1, "init" => 1],
            ["branch_id" => 1, "document_type_id" => 2, "code" => "FA", "number" => 1, "init" => 1]
        ]);

        DB::table("items")->insert([
            ["company_id" => 1, "internal_code" => "NDWI", "name" => "Agua", "description" => "", "price" => 1, "currency_id" => 1, "type" => "product", "duration_type" => null, "duration_value" => null],
            ["company_id" => 1, "internal_code" => "LQMW", "name" => "Proteina", "description" => "", "price" => 120, "currency_id" => 1, "type" => "product", "duration_type" => null, "duration_value" => null],
            ["company_id" => 1, "internal_code" => "ZXCE", "name" => "Tomatodo", "description" => "", "price" => 20, "currency_id" => 1, "type" => "product", "duration_type" => null, "duration_value" => null],
            ["company_id" => 1, "internal_code" => "WERK", "name" => "Una hora", "description" => "", "price" => 2, "currency_id" => 1, "type" => "subscription", "duration_type" => "hour", "duration_value" => 1],
            ["company_id" => 1, "internal_code" => "ABCD", "name" => "Un dia", "description" => "", "price" => 10, "currency_id" => 1, "type" => "subscription", "duration_type" => "day", "duration_value" => 1],
            ["company_id" => 1, "internal_code" => "PLDK", "name" => "Solamente hoy", "description" => "", "price" => 5, "currency_id" => 1, "type" => "subscription", "duration_type" => "today", "duration_value" => 1],
            ["company_id" => 1, "internal_code" => "EFGH", "name" => "Mes", "description" => "", "price" => 60, "currency_id" => 1, "type" => "subscription", "duration_type" => "month", "duration_value" => 1],
            ["company_id" => 1, "internal_code" => "IJKL", "name" => "Año", "description" => "", "price" => 400, "currency_id" => 1, "type" => "subscription", "duration_type" => "year", "duration_value" => 1]
        ]);

        DB::table("customers")->insert([
            ["company_id" => 1, "identity_document_type_id" => 1, "document_number" => "999999999", "name" => "Cliente varios", "phone_number" => ""],
            ["company_id" => 1, "identity_document_type_id" => 2, "document_number" => "71883137", "name" => "Gianfranco Mejia Carhuajulca", "phone_number" => "51987057624"],
            ["company_id" => 1, "identity_document_type_id" => 2, "document_number" => "71883136", "name" => "Andy Paolo Mejia Carhuajulca", "phone_number" => "51987634253"]
        ]);

        DB::table("warehouses")->insert([
            ["branch_id" => 1, "name" => "Almacén - Sede principal"]
        ]);

        DB::table("warehouse_items")->insert([
            ["warehouse_id" => 1, "item_id" => 1],
            ["warehouse_id" => 1, "item_id" => 2],
            ["warehouse_id" => 1, "item_id" => 3]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("warehouse_items");
        Schema::dropIfExists("warehouses");
        Schema::dropIfExists("customers");
        Schema::dropIfExists("items");
        Schema::dropIfExists("series");
        Schema::dropIfExists("branches");
        Schema::dropIfExists("company_socials_media");

    }

};
