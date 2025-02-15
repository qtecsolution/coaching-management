<?php

use App\Models\Course;
use App\Models\Level;
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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->constrained()->restrictOnDelete();
            $table->string('title');
            $table->double('price')->default(0);
            $table->enum('discount_type', ['flat', 'percentage'])->default('flat');
            $table->double('discount')->default(0);
            $table->bigInteger('total_students')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
