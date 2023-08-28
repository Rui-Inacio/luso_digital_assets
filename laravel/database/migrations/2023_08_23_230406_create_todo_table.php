<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        echo \App\Enums\TodoStatus::Pending->value;
        Schema::create('todo', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('status', ['done', 'pending'])->default(\App\Enums\TodoStatus::Pending->value);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('todo');
    }
};
