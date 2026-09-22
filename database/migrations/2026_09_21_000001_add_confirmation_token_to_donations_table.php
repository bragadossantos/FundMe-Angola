<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('confirmation_token', 64)->nullable()->after('payment_reference');
        });

        // Backfill any pre-existing rows so the unguessable token is never empty.
        DB::table('donations')->whereNull('confirmation_token')->orderBy('id')->get(['id'])->each(function ($donation) {
            DB::table('donations')->where('id', $donation->id)->update([
                'confirmation_token' => Str::random(40) . $donation->id,
            ]);
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->unique('confirmation_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['confirmation_token']);
            $table->dropColumn('confirmation_token');
        });
    }
};
