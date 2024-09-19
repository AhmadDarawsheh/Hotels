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
        Schema::table('rooms', function (Blueprint $table) {
            $table->enum('type', ['single', 'double', 'suite', 'family'])->change();
            $table->renameColumn('availabe_rooms', 'available_rooms');
            $table->dropColumn('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {

            $table->string('type')->change();
            $table->integer('size');
        });
    }
};
