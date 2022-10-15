<?php

use App\Enums\PaymentStatus;
use App\Models\Reservation;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Reservation::class);
            $table->string('name');
            $table->string('type');
            $table->string('status')->default(PaymentStatus::unpaid->value);
            $table->integer('amount_to_pay')
                ->comment('Divide by 100 to get the exact amount in decimal value.');
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
        Schema::dropIfExists('payments');
    }
};
