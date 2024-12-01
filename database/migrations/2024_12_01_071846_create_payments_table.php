<?php

use App\Models\StudentBatch;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StudentBatch::class)->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->double('amount');
            $table->string('month'); // month = "2024-12" here 12 is month of 2024;
            $table->date('date');
            $table->string('payment_method')->default('Cash');
            $table->text('note')->nullable();
            $table->double('due_amount')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
