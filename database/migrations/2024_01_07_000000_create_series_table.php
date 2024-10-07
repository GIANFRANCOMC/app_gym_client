<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create("series", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("branch_id");
            $table->unsignedBigInteger("document_type_id");
            $table->string("number");
            $table->integer("init")->default(1);

            $table->enum("status", ["active", "inactive"])->default("active");
            $table->timestamp("created_at")->useCurrent()->nullable();
            $table->integer("created_by")->nullable();
            $table->timestamp("updated_at")->nullable();
            $table->integer("updated_by")->nullable();

            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
            $table->foreign("document_type_id")->references("id")->on("document_types")->onDelete("cascade");
        });

        DB::table('series')->insert([
            ['branch_id' => 1, 'document_type_id' => 1, 'number' => 'FA01'],
            ['branch_id' => 1, 'document_type_id' => 2, 'number' => 'BE01'],
            ['branch_id' => 1, 'document_type_id' => 3, 'number' => 'NV01']
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

        Schema::dropIfExists("series");

    }

};
