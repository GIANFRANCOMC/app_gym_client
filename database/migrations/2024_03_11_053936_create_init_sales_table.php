<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        // ✅
        Schema::create("sales_header", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("serie_id");
            $table->integer("sequential");
            $table->unsignedBigInteger("holder_id");
            $table->unsignedBigInteger("seller_id");
            $table->unsignedBigInteger("currency_id");
            $table->date("issue_date");
            $table->decimal("total", 10, 2);
            $table->text("observation")->nullable();
            $table->enum("status", ["active", "canceled", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamp("canceled_at")->nullable();
            $table->integer("canceled_by")->nullable();

            $table->foreign("serie_id")->references("id")->on("series")->onDelete("cascade");
            $table->foreign("holder_id")->references("id")->on("customers")->onDelete("cascade");
            $table->foreign("seller_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("currency_id")->references("id")->on("currencies")->onDelete("cascade");
        });

        // ✅
        Schema::create("sales_body", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sale_header_id");
            $table->unsignedBigInteger("item_id");
            $table->unsignedBigInteger("currency_id");
            $table->string("name");
            $table->decimal("quantity", 10, 2);
            $table->decimal("price", 10, 2);
            $table->decimal("total", 10, 2);
            $table->unsignedBigInteger("customer_id");
            $table->enum("type", ["product", "service", "subscription"])->default("product");
            $table->text("observation")->nullable();
            $table->text("extras");
            $table->enum("status", ["active", "canceled", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamp("canceled_at")->nullable();
            $table->integer("canceled_by")->nullable();

            $table->foreign("sale_header_id")->references("id")->on("sales_header")->onDelete("cascade");
            $table->foreign("item_id")->references("id")->on("items")->onDelete("cascade");
            $table->foreign("currency_id")->references("id")->on("currencies")->onDelete("cascade");
            $table->foreign("customer_id")->references("id")->on("customers")->onDelete("cascade");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("sales_body");
        Schema::dropIfExists("sales_header");

    }

};
