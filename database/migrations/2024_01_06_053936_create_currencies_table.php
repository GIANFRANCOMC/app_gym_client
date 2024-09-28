<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("currencies", function (Blueprint $table) {
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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("currencies");

    }

};
