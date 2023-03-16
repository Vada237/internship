<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtask_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('subtask_id');
            $table->integer('attribute_id');
            $table->string('value');

            $table->foreign('subtask_id')->references('id')->on('subtasks')->cascadeOnDelete();
            $table->foreign('attribute_id')->references('id')->on('attributes')->cascadeOnDelete();

            $table->unique(['subtask_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtask_attributes');
    }
};
