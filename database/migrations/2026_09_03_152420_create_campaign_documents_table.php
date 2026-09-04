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
        Schema::create('campaign_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('document_type');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_mime')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->boolean('is_private')->default(true);
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_documents');
    }
};
