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
            $table->string('company_name')->nullable()->after('role');
            $table->string('founder_name')->nullable()->after('company_name');
            $table->string('company_sector')->nullable()->after('founder_name');
            $table->text('company_address')->nullable()->after('company_sector');
            $table->string('website_link')->nullable()->after('company_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'founder_name',
                'company_sector',
                'company_address',
                'website_link'
            ]);
        });
    }
};
