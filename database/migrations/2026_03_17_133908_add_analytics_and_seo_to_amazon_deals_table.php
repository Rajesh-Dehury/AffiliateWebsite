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
        Schema::table('amazon_deals', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('product_title');
            $table->unsignedBigInteger('views_count')->default(0)->after('our_post');
            $table->unsignedBigInteger('clicks_count')->default(0)->after('views_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amazon_deals', function (Blueprint $table) {
            $table->dropColumn(['slug', 'views_count', 'clicks_count']);
        });
    }
};
