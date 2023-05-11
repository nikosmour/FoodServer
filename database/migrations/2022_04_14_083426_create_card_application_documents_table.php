<?php

use App\Enum\CardDocumentStatusEnum;
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
        Schema::create('card_application_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_application_id');
            $table->enum('status', CardDocumentStatusEnum::values()->toArray())->default('submitted');
            $table->char('file_name', 100);
            $table->char('description', 27);
            $table->timestamps();
            $table->foreign('card_application_id')->references('id')->on('card_applications')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_application_documents');
    }
};
