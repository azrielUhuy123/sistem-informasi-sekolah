<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kepegawaian_id')
                ->constrained('data_kepegawaian')
                ->restrictOnDelete();
            $table->foreignId('submitted_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('submitted_by_name');
            $table->string('status', 30)->default('pending');
            $table->timestamps();
            $table->index(['data_kepegawaian_id', 'status'], 'pegawai_change_requests_employee_status_idx');
        });

        Schema::create('pegawai_change_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_change_request_id')
                ->constrained('pegawai_change_requests')
                ->cascadeOnDelete();
            $table->string('field', 100);
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->string('evidence_path')->nullable();
            $table->string('status', 30)->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('resubmitted_from_id')->nullable()
                ->constrained('pegawai_change_items')
                ->nullOnDelete();
            $table->timestamps();
            $table->unique(['pegawai_change_request_id', 'field'], 'pegawai_change_request_field_unique');
            $table->index(['field', 'status'], 'pegawai_change_items_field_status_idx');
            $table->index('resubmitted_from_id');
        });

        Schema::create('pegawai_change_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_change_item_id')
                ->constrained('pegawai_change_items')
                ->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('action', 30);
            $table->text('reason')->nullable();
            $table->timestamp('acted_at');
            $table->timestamps();
            $table->index(['pegawai_change_item_id', 'acted_at'], 'pegawai_change_decisions_item_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai_change_decisions');
        Schema::dropIfExists('pegawai_change_items');
        Schema::dropIfExists('pegawai_change_requests');
    }
};
