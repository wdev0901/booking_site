<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field_type');
            $table->string('options')->nullable();
            $table->string('label')->nullable();
            $table->string('table_name');
            $table->integer('service_id')->nullable();
            $table->tinyInteger('is_enable')->default(1);
            $table->tinyInteger('show_in_table')->default(0);
            $table->tinyInteger('visible_in_client')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_fields');
    }
}
