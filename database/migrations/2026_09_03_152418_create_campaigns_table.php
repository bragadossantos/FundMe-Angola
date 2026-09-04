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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->onDelete('cascade');
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('story');
            $table->string('category');
            $table->decimal('target_amount', 12, 2);
            $table->decimal('raised_amount', 12, 2)->default(0.00);
            $table->string('currency', 10)->default('Kz');
            $table->enum('status', [
                'draft',
                'pending_review',
                'waiting_documents',
                'under_review',
                'approved',
                'rejected',
                'suspended',
                'published',
                'goal_reached',
                'payment_processing',
                'completed',
                'closed'
            ])->default('pending_review');
            $table->enum('payment_destination_type', [
                'hospital_direct',
                'beneficiary_transfer',
                'split_payment'
            ])->nullable();
            $table->string('location_province');
            $table->string('location_municipality')->nullable();
            $table->string('hospital_name')->nullable();
            $table->enum('treatment_location', ['angola', 'estrangeiro'])->default('angola');
            $table->date('expected_treatment_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('verification_badge')->default(false);
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
