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
        Schema::create('subtask_template_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('subtask_template_id');
            $table->integer('attribute_id');
            $table->string('value');

            $table->foreign('subtask_template_id')->references('id')
                ->on('subtask_templates')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')
                ->on('attributes')->onDelete('cascade');

            $table->unique(['subtask_template_id', 'attribute_id']);
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
