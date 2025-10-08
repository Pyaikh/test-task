<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_models', function (Blueprint $table) {
            $table->unsignedBigInteger('comfort_category_id')->nullable()->after('name');
            $table->foreign('comfort_category_id')->references('id')->on('comfort_categories')->nullOnDelete();
        });

        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'comfort_category_id')) {
                $table->dropForeign(['comfort_category_id']);
                $table->dropColumn('comfort_category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->unsignedBigInteger('comfort_category_id')->nullable()->after('model_id');
            $table->foreign('comfort_category_id')->references('id')->on('comfort_categories')->nullOnDelete();
        });

        Schema::table('car_models', function (Blueprint $table) {
            if (Schema::hasColumn('car_models', 'comfort_category_id')) {
                $table->dropForeign(['comfort_category_id']);
                $table->dropColumn('comfort_category_id');
            }
        });
    }
};


