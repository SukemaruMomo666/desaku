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
            $table->enum('gender', ['L', 'P'])->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('gender');
            $table->text('address')->nullable()->after('birth_date');
            $table->string('religion')->nullable()->after('address');
            $table->string('job')->nullable()->after('religion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
