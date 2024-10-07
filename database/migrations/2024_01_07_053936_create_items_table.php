<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("items", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->decimal("price", 10, 2);

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

        Schema::dropIfExists("items");

    }

};
