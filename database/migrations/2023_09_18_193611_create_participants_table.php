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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            // $table->enum('age_group', ['16-20', '20-25', '25-30','30-40','40-100']);
            $table->date('age_group');
            $table->enum('gender', ['male','female']);
            $table->boolean('age_confirm');
            $table->boolean('english_confirm');
            $table->string('code');
            $table->float('score');
            $table->float('eventual_score')->nullable();
            $table->enum('status', ['in progress','completed','finish watch']);
            $table->integer('user_id')->nullable();
            $table->dateTime('finish_watch_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
