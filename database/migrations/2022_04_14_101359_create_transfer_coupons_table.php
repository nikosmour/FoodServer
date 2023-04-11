<?php

use App\Enum\MealPlanPeriodEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_coupon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            foreach (MealPlanPeriodEnum::names() as $period)
                $table->tinyInteger($period)->unsigned()->default(0);
            $table->timestamp('created_at')->useCurrent();
//            $table->timestamps();
            $table->foreign('sender_id')->references('academic_id')->on('coupon_owners')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('receiver_id')->references('academic_id')->on('coupon_owners')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['created_at', 'sender_id', 'receiver_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_coupon');
    }
};
