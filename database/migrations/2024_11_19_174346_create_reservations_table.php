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
        Schema::create('reservations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('RESTRICT'); 
            $table->integer('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('reservation_types')->onDelete('RESTRICT'); 
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->references('id')->on('reservation_statuses')->onDelete('RESTRICT'); 
            $table->date('reservation_date');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('total_nights')->nullable(); // For short term
            $table->integer('total_months')->nullable();
            $table->integer('notice_period_days')->unsigned()->default(0); 
            $table->decimal('total_amount', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->dateTime('actual_check_in')->nullable();    // When customer actually checked in
            $table->dateTime('actual_check_out')->nullable();   // When customer actually checked out
            $table->boolean('is_checked_in')->default(false);   // Confirmation status for check-in
            $table->boolean('is_checked_out')->default(false); 
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
