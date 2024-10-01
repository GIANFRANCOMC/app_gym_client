<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("company_socials_media", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->enum("type", ["web", "facebook", "instagram", "tiktok", "other"])->default("other");
            $table->text("link");
            $table->enum("status", ["active", "inactive"])->default("active");

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

        Schema::dropIfExists("company_socials_media");

    }

};
