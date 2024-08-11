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
        Schema::create('amazon_deals', function (Blueprint $table) {
            $table->id();
            $table->string('product_asin')->nullable();
            $table->string('product_asin_hash')->nullable();
            $table->text('detail_page_url')->nullable();
            $table->text('primary_large_url')->nullable();
            $table->text('product_title')->nullable();
            $table->string('mrp')->nullable();
            $table->string('offer_price')->nullable();
            $table->string('saving_percent')->nullable();
            $table->string('saving_amount')->nullable();
            $table->text('features_editor')->nullable();
            $table->string('our_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_deals');
    }
};
