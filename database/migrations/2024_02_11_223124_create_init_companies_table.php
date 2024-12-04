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
            $table->text("description");
            $table->decimal("price", 10, 2);
            $table->unsignedBigInteger("currency_id");
            $table->enum("type", ["product", "service"])->default("product");
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
            $table->unsignedBigInteger("identity_document_type_id");
            $table->string("document_number");
            $table->string("name");
            $table->string("email")->nullable();
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("identity_document_type_id")->references("id")->on("identity_document_types")->onDelete("cascade");
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

        DB::table("customers")->insert([
            ["identity_document_type_id" => 1, "document_number" => "999999999", "name" => "Cliente varios"]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("customers");
        Schema::dropIfExists("items");
        Schema::dropIfExists("series");
        Schema::dropIfExists("branches");
        Schema::dropIfExists("company_socials_media");

    }

};
