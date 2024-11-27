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
        Schema::create('reservation_extensions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('CASCADE');
            $table->date('original_check_out_date');
            $table->date('extended_check_out_date');
            $table->integer('extension_months');
            $table->decimal('extra_nights', 10, 2);
            $table->decimal('total_extension_amount', 10, 2);
            $table->integer('created_by')->unsigned()->nullable(); // معرف المستخدم الذي أضاف التمديد
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_extensions');
    }
};
