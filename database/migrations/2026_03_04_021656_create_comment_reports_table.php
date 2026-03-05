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
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // yg report
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->enum('reason', ['spam', 'sara', 'kasar', 'offensive', 'other'])->default('other');
            $table->text('custom_reason')->nullable(); // kalo pilih other
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable(); // catatan dari admin
            $table->foreignId('handled_by')->nullable()->constrained('users'); // admin yg handle
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
    }
};
