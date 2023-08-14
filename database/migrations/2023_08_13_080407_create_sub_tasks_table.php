<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("task_id")->constrained("tasks")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("assign_to")->nullable()->constrained("users")->nullOnDelete()->cascadeOnUpdate();
            $table->text("title");
            $table->longText("description")->nullable();
            $table->timestamp("due_date")->nullable();
            $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId("updated_by")->nullable()->constrained("users")->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_tasks');
    }
};
