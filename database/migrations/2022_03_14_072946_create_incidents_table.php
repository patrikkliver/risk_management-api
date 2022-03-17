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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->text('incident');
            $table->char('reported_by');
            $table->date('date_incident');
            $table->string('position', 255);
            $table->text('recommendation');
            $table->text('description')->nullable();
            $table->enum('cause', ['APD', 'Environment', 'Posisi Kerja', 'Peralatan', 'Prosedur', 'Reaksi Pekerja']);
            $table->enum('system', ['Electrical', 'Instrument', 'Civil', 'Safety', 'Security']);
            $table->enum('source', ['Near Miss', 'Unsafe Activity', 'Unsafe Condition', 'Tinjauan Management', 'Keluhan Masyarakat', 'Internal Audit', 'Eksternal Audit']);
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
        Schema::dropIfExists('incidents');
    }
};
