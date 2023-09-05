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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()
                ->constrained()->nullOnDelete();
            //commentable_id(unsigned big int) + commentable_type(varchar 255)
            $table->morphs('commentable');
            $table->text('content');
            $table->string('ip',15)->nullable(); //ipv4 = xxx.xxx.xxx.xxx
            $table->string('user_agent',512)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
