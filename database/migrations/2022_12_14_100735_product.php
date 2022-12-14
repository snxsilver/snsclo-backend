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
        Schema::create('product', function (Blueprint $table){
            $table->string('uuid', 32)->unique()->primary();
            $table->string('sampul0');
            $table->string('sampul1')->nullable();
            $table->string('sampul2')->nullable();
            $table->string('sampul3')->nullable();
            $table->string('sampul4')->nullable();
            $table->string('sampul5')->nullable();
            $table->string('sampul6')->nullable();
            $table->string('sampul7')->nullable();
            $table->string('sampul8')->nullable();
            $table->string('title');
            $table->double('price');
            $table->mediumText('description');
            $table->double('weight');
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
        Schema::dropIfExists('product');
    }
};
