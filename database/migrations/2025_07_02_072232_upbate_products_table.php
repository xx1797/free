<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->after('description')->constrained();
            $table->foreignId('condition_id')->after('category_id')->constrained();
            $table->boolean('is_sold')->default(false)->after('stock_quantity');
            $table->dropColumn('stock_quantity');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['condition_id']);
            $table->dropColumn(['user_id', 'category_id', 'condition_id', 'is_sold']);
            $table->integer('stock_quantity')->default(0);
        });
    }
};

