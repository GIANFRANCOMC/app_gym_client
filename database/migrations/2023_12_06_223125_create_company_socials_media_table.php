<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("company_socials_media", function (Blueprint $table) {
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

        DB::table("company_socials_media")->insert([
            "company_id" => 1,
            "type" => "facebook",
            "link" => "https://www.facebook.com/GianfrancoMC"
        ]);

        DB::table("company_socials_media")->insert([
            "company_id" => 1,
            "type" => "instagram",
            "link" => "https://www.instagram.com/gianfrancomc/"
        ]);

        DB::table("company_socials_media")->insert([
            "company_id" => 1,
            "type" => "whatsapp",
            "link" => "https://wa.me/987057624"
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("company_socials_media");

    }

};
