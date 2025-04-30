<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("subscriptions", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("branch_id");
            $table->unsignedBigInteger("sale_header_id");
            $table->unsignedBigInteger("sale_body_id");
            $table->unsignedBigInteger("customer_id");
            $table->enum("duration_type", ["hour", "day", "today", "month", "year"])->nullable();
            $table->integer("duration_value")->nullable();
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->boolean("set_end_of_day")->default(false);
            $table->boolean("force")->default(false);
            $table->integer("attendance_limit_per_day")->default(1);
            $table->text("observation")->nullable();
            $table->text("motive")->nullable();
            $table->enum("type", ["sale", "manual"])->default("sale");
            $table->enum("status", ["active", "canceled", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamp("canceled_at")->nullable();
            $table->integer("canceled_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
            $table->foreign("sale_header_id")->references("id")->on("sales_header")->onDelete("cascade");
            $table->foreign("sale_body_id")->references("id")->on("sales_body")->onDelete("cascade");
            $table->foreign("customer_id")->references("id")->on("customers")->onDelete("cascade");
        });

        Schema::create("attendances", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("branch_id");
            $table->unsignedBigInteger("customer_id");
            $table->dateTime("start_date")->nullable();
            $table->dateTime("end_date")->nullable();
            $table->text("observation")->nullable();
            $table->text("motive")->nullable();
            $table->enum("type", ["manual", "qr"])->default("manual");
            $table->enum("status", ["active", "canceled", "inactive", "finalized"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamp("canceled_at")->nullable();
            $table->integer("canceled_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
            $table->foreign("customer_id")->references("id")->on("customers")->onDelete("cascade");
        });

        Schema::create("emails", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->string("to");
            $table->string("subject");
            $table->text("body")->nullable();
            $table->text("extras_json")->nullable();
            $table->string("type")->nullable();
            $table->string("model_id")->nullable();
            $table->string("model_type")->nullable();
            $table->enum("status", ["pending", "sent", "failed"])->default("pending");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("emails");
        Schema::dropIfExists("attendances");
        Schema::dropIfExists("subscriptions");

    }

};
