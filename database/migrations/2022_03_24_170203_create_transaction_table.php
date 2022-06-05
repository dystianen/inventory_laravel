<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user');
            $table->foreignId('checker')->nullable();
            $table->foreignId('warehouse');
            $table->foreignId('item');
            $table->date('date');
            $table->float('total');
            $table->enum('type',['out','in','order']);
            $table->enum('status',['rejected','pending','process','accepted','done']);
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
        Schema::dropIfExists('transactions');
    }
}
