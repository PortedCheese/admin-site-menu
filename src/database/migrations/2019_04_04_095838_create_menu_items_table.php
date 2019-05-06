<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title');
            $table->integer('menu_id');
            $table->string('route')->nullable();
            $table->string('url')->nullable();
            $table->integer('weight')->default(1);
            $table->string('class')->nullable();
            $table->string('middleware')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('target')->nullable();
            $table->string('method')->nullable();
            $table->string('template')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
