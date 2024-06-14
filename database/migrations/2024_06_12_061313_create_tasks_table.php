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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('t_id');
            $table->integer('t_user_id')->comment('User ID');
            $table->string('t_title', 100);
            $table->string('t_content')->nullable();
            $table->integer('t_status')->length(1)->comment('0:done; 1:in progress; 2:to do');
            $table->string('t_file', 100)->nullable();
            $table->integer('t_is_published')->length(1)->comment('0:published; 1:draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
