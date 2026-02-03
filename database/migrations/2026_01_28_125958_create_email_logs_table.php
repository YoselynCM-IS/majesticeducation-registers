<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->longText('message_search')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('mailable')->nullable();
            $table->enum('status', ['sent', 'failed', 'retrying'])->default('sent');
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
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
        Schema::dropIfExists('email_logs');
    }
}
