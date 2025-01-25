<?php

use App\Helpers\System\Utilities;
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

        Schema::create("sections", function(Blueprint $table) {
            $table->id();
            $table->string("slug");
            $table->string("name");
            $table->integer("order")->nullable();
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
        });

        Schema::create("sub_sections", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("section_id");
            $table->string("slug");
            $table->string("name");
            $table->integer("order")->nullable();
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("section_id")->references("id")->on("sections")->onDelete("cascade");
        });

        Schema::create("companies_sub_sections", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("sub_section_id");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
            $table->foreign("sub_section_id")->references("id")->on("sub_sections")->onDelete("cascade");
        });

        Schema::create("roles", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->string("slug");
            $table->string("name");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
        });

        Schema::create("users", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("role_id")->nullable();
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
            $table->foreign("role_id")->references("id")->on("roles")->onDelete("cascade");
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
            ["id" => 1, "identity_document_type_id" => 1, "document_number" => "999999999", "legal_name" => "PAGAPE S.A.", "commercial_name" => "PAGAPE", "address" => "-", "telephone" => "-", "email" => ""]
        ]);

        DB::table("sections")->insert([
            ["id" => 1, "slug" => "sc_home", "name" => "home", "order" => 1],
            ["id" => 2, "slug" => "sc_sales", "name" => "sales", "order" => 2],
            ["id" => 3, "slug" => "sc_trackings", "name" => "trackings", "order" => 3],
            ["id" => 4, "slug" => "sc_items", "name" => "items", "order" => 4],
            ["id" => 5, "slug" => "sc_customers", "name" => "customers", "order" => 5],
            ["id" => 6, "slug" => "sc_configuration", "name" => "configuration", "order" => 6],
            ["id" => 7, "slug" => "sc_reports", "name" => "reports", "order" => 7]
        ]);

        DB::table("sub_sections")->insert([
            ["id" => 1, "slug" => "sbc_home-main", "name" => "home-main"],
            ["id" => 2, "slug" => "sbc_sales-list", "name" => "sales-list"],
            ["id" => 3, "slug" => "sbc_sales-main", "name" => "sales-main"],
            ["id" => 4, "slug" => "sbc_trackings-subscriptions", "name" => "trackings-subscriptions"],
            ["id" => 5, "slug" => "sbc_items-products", "name" => "items-products"],
            ["id" => 6, "slug" => "sbc_items-services", "name" => "items-services"],
            ["id" => 7, "slug" => "sbc_items-subscriptions", "name" => "items-subscriptions"],
            ["id" => 8, "slug" => "sbc_customers-main", "name" => "customers-main"],
            ["id" => 9, "slug" => "sbc_configuration-my_company", "name" => "configuration-my_company"],
            ["id" => 10, "slug" => "sbc_configuration-branchs", "name" => "configuration-branchs"],
            ["id" => 11, "slug" => "sbc_configuration-users", "name" => "configuration-users"],
            ["id" => 12, "slug" => "sbc_reports-main", "name" => "reports-main"]
        ]);

        DB::table("companies_sub_sections")->insert([
            ["company_id" => 1, "sub_section_id" => 1],
            ["company_id" => 1, "sub_section_id" => 2],
            ["company_id" => 1, "sub_section_id" => 3],
            ["company_id" => 1, "sub_section_id" => 4],
            ["company_id" => 1, "sub_section_id" => 5],
            ["company_id" => 1, "sub_section_id" => 6],
            ["company_id" => 1, "sub_section_id" => 7],
            ["company_id" => 1, "sub_section_id" => 8],
            ["company_id" => 1, "sub_section_id" => 9],
            ["company_id" => 1, "sub_section_id" => 10],
            ["company_id" => 1, "sub_section_id" => 11],
            ["company_id" => 1, "sub_section_id" => 12]
        ]);

        DB::table("roles")->insert([
            ["id" => 1, "company_id" => 1, "slug" => Utilities::generateCode(), "name" => "Administrador"],
            ["id" => 2, "company_id" => 1, "slug" => Utilities::generateCode(), "name" => "Vendedor"]
        ]);

        DB::table("users")->insert([
            ["company_id" => 1, "role_id" => 1, "identity_document_type_id" => 1, "document_number" => "999999999", "name" => "Usuario demo", "email" => "demo@pagape.com", "password" => Hash::make("1")],
            ["company_id" => 1, "role_id" => 2, "identity_document_type_id" => 2, "document_number" => "71883137", "name" => "Gianfranco", "email" => "gmc@pagape.com", "password" => Hash::make("1")]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("users");
        Schema::dropIfExists("roles");
        Schema::dropIfExists("companies_sections");
        Schema::dropIfExists("sub_sections");
        Schema::dropIfExists("sections");
        Schema::dropIfExists("companies");
        Schema::dropIfExists("currencies");
        Schema::dropIfExists("document_types");
        Schema::dropIfExists("identity_document_types");

    }

};
