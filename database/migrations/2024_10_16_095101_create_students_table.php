<?php

use App\Models\Batch;
use App\Models\User;
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('reg_id')->unique();
            $table->string('occupation')->nullable();
            $table->string('qualification')->nullable();
            $table->date('date_of_birth');
            $table->string('nid_number');
            $table->text('address');
            $table->string('father_name');
            $table->string('mother_name');
            $table->json('emergency_contact');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
