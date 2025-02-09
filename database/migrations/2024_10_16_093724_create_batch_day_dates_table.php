<?php

use App\Models\BatchDay;
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
        Schema::create('batch_day_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BatchDay::class)->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('day');
            $table->enum('status', ['completed', 'pending'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_day_dates');
    }
};
