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
            $table->id();
            $table->string(column: 'name');
            $table->longText(column: 'description')->nullable();
            $table->string(column: 'image_path')->nullable();
            $table->string(column: 'status');
            $table->string(column: 'priority');
            $table->timestamp(column: 'due_date')->nullable();
            $table->unsignedBigInteger(column: 'assigned_user_id');
            $table->foreign('assigned_user_id')->references('id')->on('users');
            $table->unsignedBigInteger(column: 'created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger(column: 'updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedBigInteger(column: 'project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->timestamps();
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
