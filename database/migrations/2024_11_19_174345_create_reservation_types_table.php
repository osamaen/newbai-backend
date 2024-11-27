<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservation_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name'); // 'Short Term', 'Long Term'
            $table->string('description')->nullable();
            $table->integer('default_pricing_type_id')->unsigned()->nullable();
            $table->foreign('default_pricing_type_id')->references('id')->on('pricing_types')->onDelete('RESTRICT'); 
            $table->integer('minimum_stay_days')->default(1);
            $table->integer('maximum_stay_days')->nullable();
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_types');
    }
};
