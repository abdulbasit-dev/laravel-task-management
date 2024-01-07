<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("assign_to")->nullable()->constrained("users")->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId("project_id")->constrained("projects")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("task_id")->nullable()->constrained("tasks")->nullOnDelete()->cascadeOnUpdate();
            $table->text("title");
            $table->longText("description")->nullable();
            $table->timestamp("due_date")->nullable();
            $table->integer("status")->default(0);
            $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId("updated_by")->nullable()->constrained("users")->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
