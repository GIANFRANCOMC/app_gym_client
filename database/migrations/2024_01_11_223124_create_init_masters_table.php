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
            $table->string("token_api_misc")->nullable();
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
            $table->string("dom_id")->default("");
            $table->string("dom_label")->default("");
            $table->string("dom_icon")->default("");
            $table->boolean("has_sub_menu")->default(false);
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
            $table->string("dom_id")->default("");
            $table->string("dom_label")->default("");
            $table->string("dom_icon")->default("");
            $table->string("dom_route")->default("");
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
            ["id" => 1, "slug" => "sc_home", "name" => "home", "order" => 1, "dom_id" => "menu-item-home", "dom_label" => "Inicio", "dom_icon" => "fa fa-home", "has_sub_menu" => false],
            ["id" => 2, "slug" => "sc_sales", "name" => "sales", "order" => 2, "dom_id" => "menu-item-sales", "dom_label" => "Ventas", "dom_icon" => "fa-solid fa-cash-register", "has_sub_menu" => true],
            ["id" => 3, "slug" => "sc_trackings", "name" => "trackings", "order" => 4, "dom_id" => "menu-item-trackings", "dom_label" => "Seguimiento", "dom_icon" => "fa-solid fa-binoculars", "has_sub_menu" => true],
            ["id" => 4, "slug" => "sc_items", "name" => "items", "order" => 5, "dom_id" => "menu-item-catalogs", "dom_label" => "Catálogo comercial", "dom_icon" => "fa fa-book", "has_sub_menu" => true],
            ["id" => 5, "slug" => "sc_inventories", "name" => "inventories", "order" => 6, "dom_id" => "menu-item-inventories", "dom_label" => "Inventario", "dom_icon" => "fa-solid fa-arrow-right-arrow-left", "has_sub_menu" => true],
            ["id" => 6, "slug" => "sc_infrastructure", "name" => "infrastructure", "order" => 7, "dom_id" => "menu-item-infrastructure", "dom_label" => "Infraestructura", "dom_icon" => "fa-solid fa-industry", "has_sub_menu" => true],
            ["id" => 7, "slug" => "sc_customers", "name" => "customers", "order" => 3, "dom_id" => "menu-item-customers", "dom_label" => "Clientes", "dom_icon" => "fa fa-user", "has_sub_menu" => false],
            ["id" => 8, "slug" => "sc_configuration", "name" => "configuration", "order" => 8, "dom_id" => "menu-item-configuration", "dom_label" => "Configuración", "dom_icon" => "fa fa-gear", "has_sub_menu" => true],
            ["id" => 9, "slug" => "sc_reports", "name" => "reports", "order" => 9, "dom_id" => "menu-item-reports", "dom_label" => "Reportes", "dom_icon" => "fa fa-print", "has_sub_menu" => false]
        ]);

        DB::table("sub_sections")->insert([
            ["id" => 1, "section_id" => 1, "slug" => "sbc_home-main", "name" => "home-main", "dom_id" => "menu-item-home", "dom_label" => "Inicio", "dom_route" => "home.index"],
            ["id" => 2, "section_id" => 2, "slug" => "sbc_sales-list", "name" => "sales-list", "dom_id" => "menu-item-list-sales", "dom_label" => "Listado", "dom_route" => "sales.index"],
            ["id" => 3, "section_id" => 2, "slug" => "sbc_sales-main", "name" => "sales-main", "dom_id" => "menu-item-create-sales", "dom_label" => "Nuevo", "dom_route" => "sales.create"],
            ["id" => 4, "section_id" => 3, "slug" => "sbc_trackings-subscriptions", "name" => "trackings-subscriptions", "dom_id" => "menu-item-trackings-subscriptions", "dom_label" => "Suscripciones", "dom_route" => "tracking_subscriptions.index"],
            ["id" => 5, "section_id" => 4, "slug" => "sbc_items-products", "name" => "items-products", "dom_id" => "menu-item-catalogs-products", "dom_label" => "Productos", "dom_route" => "products.index"],
            ["id" => 6, "section_id" => 4, "slug" => "sbc_items-services", "name" => "items-services", "dom_id" => "menu-item-catalogs-services", "dom_label" => "Servicios", "dom_route" => "services.index"],
            ["id" => 7, "section_id" => 4, "slug" => "sbc_items-subscriptions", "name" => "items-subscriptions", "dom_id" => "menu-item-catalogs-subscriptions", "dom_label" => "Suscripciones", "dom_route" => "subscriptions.index"],
            ["id" => 8, "section_id" => 5, "slug" => "sbc_inventories-stocks_management", "name" => "inventories-stocks_management", "dom_id" => "menu-item-inventories-stocks_management", "dom_label" => "Gestión de stock", "dom_route" => "stocks_management.index"],
            ["id" => 9, "section_id" => 6, "slug" => "sbc_infrastructure-assets", "name" => "infrastructure-assets", "dom_id" => "menu-item-infrastructure-assets", "dom_label" => "Activos", "dom_route" => "assets.index"],
            ["id" => 10, "section_id" => 6, "slug" => "sbc_infrastructure-assets_management", "name" => "infrastructure-assets_management", "dom_id" => "menu-item-infrastructure-assets_management", "dom_label" => "Gestión de activos", "dom_route" => "assets_management.index"],
            ["id" => 11, "section_id" => 7, "slug" => "sbc_customers-main", "name" => "customers-main", "dom_id" => "menu-item-customers", "dom_label" => "Clientes", "dom_route" => "customers.index"],
            ["id" => 12, "section_id" => 8, "slug" => "sbc_configuration-my_company", "name" => "configuration-my_company", "dom_id" => "menu-item-configuration-companies", "dom_label" => "Mi empresa", "dom_route" => "companies.index"],
            ["id" => 13, "section_id" => 8, "slug" => "sbc_configuration-branchs", "name" => "configuration-branchs", "dom_id" => "menu-item-configuration-branches", "dom_label" => "Sucursales", "dom_route" => "branches.index"],
            ["id" => 14, "section_id" => 8, "slug" => "sbc_configuration-users", "name" => "configuration-users", "dom_id" => "menu-item-configuration-users", "dom_label" => "Colaboradores", "dom_route" => "users.index"],
            ["id" => 15, "section_id" => 9, "slug" => "sbc_reports-main", "name" => "reports-main", "dom_id" => "menu-item-reports", "dom_label" => "Reportes", "dom_route" => "reports.index"],
            ["id" => 16, "section_id" => 3, "slug" => "sbc_trackings-attendances", "name" => "trackings-attendances", "dom_id" => "menu-item-trackings-attendances", "dom_label" => "Asistencia", "dom_route" => "tracking_attendances.index"]
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
            ["company_id" => 1, "sub_section_id" => 12],
            ["company_id" => 1, "sub_section_id" => 13],
            ["company_id" => 1, "sub_section_id" => 14],
            ["company_id" => 1, "sub_section_id" => 15],
            ["company_id" => 1, "sub_section_id" => 16]
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
        Schema::dropIfExists("companies_sub_sections");
        Schema::dropIfExists("sub_sections");
        Schema::dropIfExists("sections");
        Schema::dropIfExists("companies");
        Schema::dropIfExists("currencies");
        Schema::dropIfExists("document_types");
        Schema::dropIfExists("identity_document_types");

    }

};
