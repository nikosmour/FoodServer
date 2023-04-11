<?php

use App\Models\CardApplication;
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
        Schema::create('has_card_applicant_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CardApplication::class);
            $table->string('card_applicant_comment');
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('card_application_id')->references('id')->on('card_applications')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('has_card_applicant_comments');
    }
};
