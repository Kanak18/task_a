<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained();
            $table->foreignId('product_id')->nullable()->constrained();
            $table->string('path_256');
            $table->string('path_512');
            $table->string('path_1024');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['upload_id','product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
