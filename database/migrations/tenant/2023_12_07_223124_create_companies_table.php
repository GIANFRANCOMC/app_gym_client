<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->enum('document_type', ['dni', 'ruc', 'none'])->default('none');
            $table->string('document_number')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('commercial_name')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists('companies');

    }

};
