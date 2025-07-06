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
        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });
        
        // Update existing notes to assign them to the first user (or you can delete them)
        // For now, let's assign them to the first user if any exist
        if (DB::table('notes')->count() > 0 && DB::table('users')->count() > 0) {
            $firstUserId = DB::table('users')->first()->id;
            DB::table('notes')->update(['user_id' => $firstUserId]);
        }
        
        // Now make the column not nullable
        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
