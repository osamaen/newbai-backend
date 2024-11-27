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
        Schema::create('bedspace_prices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('bed_space_id')->unsigned()->nullable();
            $table->foreign('bed_space_id')->references('id')->on('bed_spaces')->onDelete('RESTRICT');
            $table->integer('pricing_type_id')->unsigned()->nullable();
            $table->foreign('pricing_type_id')->references('id')->on('pricing_types')->onDelete('RESTRICT');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bedspace_prices');
    }
};
