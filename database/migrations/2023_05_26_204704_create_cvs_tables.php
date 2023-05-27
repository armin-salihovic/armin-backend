<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvsTables extends Migration
{
    public function up()
    {
        Schema::create('cvs', function (Blueprint $table) {
            createDefaultTableFields($table, true, false);
            $table->string('title', 200)->nullable();
        });

    }

    public function down()
    {
        Schema::dropIfExists('cvs');
    }
}
