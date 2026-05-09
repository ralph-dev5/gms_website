<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('street_report_logs', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->date('reported_date');
            $table->unsignedBigInteger('report_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('street_report_logs');
    }
};