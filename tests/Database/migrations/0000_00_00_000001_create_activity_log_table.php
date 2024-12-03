<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->json('properties')->nullable();
            $table->index('log_name');
            $table->string('event')->nullable();
            $table->uuid('batch_uuid')->nullable();

            $table->string('causer_type')->nullable();
            $table->unsignedInteger('causer_id')->nullable();
            $table->index(['causer_type', 'causer_id'], 'ix_causer_type_id');

            $table
                ->foreign('causer_id', 'fk_activity_causer')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
