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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('project_categories')->nullOnDelete();
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('year')->nullable();
            $table->string('client_name')->nullable();
            $table->string('land_area')->nullable();
            $table->string('building_area')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
