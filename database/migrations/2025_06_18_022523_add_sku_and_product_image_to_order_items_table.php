<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('product_name');
            $table->string('product_image')->nullable()->after('sku');
        });
    }
    
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->dropColumn('product_image');
        });
    }
    
};
