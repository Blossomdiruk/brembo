<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_menu', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('icon');
            $table->string('title');
            $table->integer('page_id');
            $table->string('url');
            $table->integer('parent_id');
            $table->char('is_parent', 1);
            $table->char('show_menu', 1);
            $table->integer('parent_order');
            $table->integer('child_order');
            $table->float('fOrder', 8, 2);
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
        Schema::dropIfExists('dynamic_menu');
    }
}
