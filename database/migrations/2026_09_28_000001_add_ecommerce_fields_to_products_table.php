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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('stock')->default(0)->after('price');
            $table->decimal('discount', 5, 2)->default(0)->after('stock');
            $table->boolean('is_featured')->default(false)->after('discount');
            $table->unsignedBigInteger('total_sold')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock', 'discount', 'is_featured', 'total_sold']);
        });
    }
};
