<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->json("active")
                ->comment("Список путей для которых ссылка активна")
                ->after("route")
                ->nullable();

            $table->boolean("single")
                ->default(0)
                ->comment("Отдельный путь")
                ->after("active")
                ->nullable();

            $table->char("ico", 50)
                ->nullable()
                ->after("single")
                ->comment("Иконка");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn("active");
            $table->dropColumn("single");
            $table->dropColumn("ico");
        });
    }
}
