<?php

use App\Models\Batch;
use App\Models\Student;
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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Batch::class)->constrained()->cascadeOnDelete();
            $table->double('amount')->default(0);
            $table->enum('discount_type', ['flat', 'percentage'])->default('flat');
            $table->double('discount')->default(0);
            $table->double('total_paid')->default(0);
            $table->double('total_due')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
