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
            $table->string('place_of_birth')->nullable()->after('birth_date');
            $table->enum('blood_type', ['A', 'B', 'AB', 'O', '-'])->nullable()->after('gender');
            $table->string('rt', 3)->nullable()->after('address');
            $table->string('rw', 3)->nullable()->after('rt');
            $table->string('village')->nullable()->after('rw');
            $table->string('district')->nullable()->after('village');
            $table->string('city')->nullable()->after('district');
            $table->string('province')->nullable()->after('city');
            $table->string('marital_status')->nullable()->after('religion');
            $table->string('nationality')->default('WNI')->after('job');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'place_of_birth',
                'blood_type',
                'rt',
                'rw',
                'village',
                'district',
                'city',
                'province',
                'marital_status',
                'nationality'
            ]);
        });
    }
};
