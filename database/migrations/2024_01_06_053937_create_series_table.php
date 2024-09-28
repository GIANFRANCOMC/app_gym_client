<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("series", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("branch_id");
            $table->string("name");
            $table->string("code");
            $table->enum("status", ["active", "inactive"])->default("active");

            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("series");

    }

};
