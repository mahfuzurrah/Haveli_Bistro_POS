<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id');
            $table->tinyInteger('shift')->comment('1 => Shift 1, 2 => Shift 2');
            $table->text('open_notes')->nullable();
            $table->double('open_amount')->default(0);
            $table->dateTime('open_time')->nullable();
            $table->text('close_notes')->nullable();
            $table->double('close_amount')->default(0);
            $table->dateTime('close_time')->nullable();
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
        Schema::dropIfExists('registers');
    }
};
