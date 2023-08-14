<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("task_id")->constrained("tasks")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("assign_to")->nullable()->constrained("users")->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId("action_by")->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->text("title")->nullable();
            $table->text("description")->nullable();
            $table->timestamp("due_date")->nullable();
            $table->integer("status")->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_logs');
    }
};
