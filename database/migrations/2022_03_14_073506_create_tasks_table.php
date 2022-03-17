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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->enum('risk_level', ['low', 'low-to-medium', 'medium', 'medium-to-high', 'high']);
            $table->date('due_date');
            $table->enum('status', ['Open', 'Close', 'Overdue'])->default('Open');
            $table->boolean('is_done')->default(0);
            $table->unsignedBigInteger('incident_id');
            $table->char('pic');
            $table->timestamps();

            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
