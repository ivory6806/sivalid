<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('email');
            $table->string('nip')->nullable()->after('role');
            $table->string('jabatan')->nullable()->after('nip');
            $table->boolean('is_active')->default(true)->after('jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nip', 'jabatan', 'is_active']);
        });
    }
};