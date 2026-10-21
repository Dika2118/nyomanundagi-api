<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_stats', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();        // e.g. 'customers', 'years_founded', 'projects', 'awards'
            $table->string('label');                // e.g. 'Customers', 'Tahun Berdiri'
            $table->string('value');                // e.g. '35000', '2010', '450'
            $table->string('suffix')->nullable();   // e.g. 'k+', '+', ''
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_stats');
    }
};
