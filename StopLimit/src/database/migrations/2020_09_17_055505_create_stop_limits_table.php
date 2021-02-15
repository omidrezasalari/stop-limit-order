<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_limits', function (Blueprint $table) {
            $table->id();
            $table->string('stop_price');
            $table->string('limit_price');
            $table->string('amount');
            $table->string('owner');
            $table->boolean('type');
            $table->unsignedSmallInteger('status')->default(0);
            $table->uuid('client_order_id')->unique();
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
        Schema::dropIfExists('stop_limits');
    }
}
