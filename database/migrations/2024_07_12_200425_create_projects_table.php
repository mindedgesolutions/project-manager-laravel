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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'name');
            $table->longText(column: 'description')->nullable();
            $table->timestamp(column: 'due_date')->nullable();
            $table->string(column: 'status');
            $table->string(column: 'image_path')->nullable();
            $table->unsignedBigInteger(column: 'created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger(column: 'updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
