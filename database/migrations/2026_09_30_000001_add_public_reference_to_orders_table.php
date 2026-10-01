<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('public_reference', 24)->nullable()->unique()->after('id');
        });

        DB::table('orders')->whereNull('public_reference')->orderBy('id')->eachById(function (object $order) {
            DB::table('orders')
                ->where('id', $order->id)
                ->update(['public_reference' => 'ORD-' . Str::upper(Str::random(10))]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['public_reference']);
            $table->dropColumn('public_reference');
        });
    }
};
