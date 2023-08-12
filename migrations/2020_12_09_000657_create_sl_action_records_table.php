<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlActionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_action_records', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->unsignedBigInteger('sl_action_type_id');
            $table->foreign('sl_action_type_id')
                ->on('sl_action_types')
                ->references('id');
            $table->text('payload');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
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
        Schema::dropIfExists('action_records');
    }
}
