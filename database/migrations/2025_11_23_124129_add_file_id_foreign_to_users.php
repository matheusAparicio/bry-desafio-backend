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
            if (!Schema::hasColumn('users', 'file_id')) {
                $table->unsignedBigInteger('file_id')->nullable()->after('address');
            } else {
                $table->unsignedBigInteger('file_id')->nullable()->change();
            }

            $table->foreign('file_id')
                ->references('id')
                ->on('document_files')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
        });
    }
};
