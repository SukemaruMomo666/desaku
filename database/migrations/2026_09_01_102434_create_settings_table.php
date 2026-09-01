<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // Insert default permissions
        DB::table('settings')->insert([
            [
                'key' => 'role_super_admin_permissions',
                'value' => json_encode(['manage_requests', 'manage_letter_types', 'manage_users'])
            ],
            [
                'key' => 'role_admin_permissions',
                'value' => json_encode(['manage_requests'])
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
