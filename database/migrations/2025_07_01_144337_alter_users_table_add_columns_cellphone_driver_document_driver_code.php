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
        Schema::table('users', function(Blueprint $table) {
            $table->string('cellphone')->after('password');
            $table->string('driver_document')->after('document');
            $table->string('driver_document_code')->after('driver_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('cellphone');
            $table->dropColumn('driver_document');
            $table->dropColumn('driver_document_code');
        });
    }
};
