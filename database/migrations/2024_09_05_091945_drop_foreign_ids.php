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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'hotel_id')) {
                $table->dropForeign(['hotel_id']);
                $table->dropColumn('hotel_id');
            }
        });

        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('hotel_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
