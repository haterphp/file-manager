<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenDirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children_dirs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['folder', 'file']);
            $table->unsignedBigInteger('parent');
            $table->unsignedBigInteger('child_dir')->nullable();
            $table->unsignedBigInteger('child_file')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent')->references('id')->on('dirs')->onDelete('cascade');
            $table->foreign('child_dir')->references('id')->on('dirs')->onDelete('cascade');
            $table->foreign('child_file')->references('id')->on('files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('children_dirs');
    }
}
