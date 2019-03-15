<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TodoList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ToDO::create('to_dos', function (Blueprint $table) {
            $table->bigint('ID')->index();
            $table->string('title')
            $table->text('info');
            $table->boolean('completed');
            $table->longText('image')->nullable();
            $table->boolean('editing');
            $table->boolean('editinginfo');
            $table->timestamp('created_at')->nullable();
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
}
