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
        Schema::create('task_template_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('task_template_id');
            $table->integer('task_attribute_id');
            $table->string('value');

            $table->foreign('task_template_id')->references('id')
                ->on('task_templates')->onDelete('cascade');
            $table->foreign('task_attribute_id')->references('id')
                ->on('task_attributes')->onDelete('cascade');

            $table->unique(['task_template_id', 'task_attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
