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
        Schema::create('sla_policies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('priority');

            $table->unsignedInteger('first_response_minutes');
            $table->unsignedInteger('resolution_minutes');

            $table->timestamps();

            $table->unique(['organization_id', 'priority']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('sla_policies', function (Blueprint $table) {
        $table->dropForeign(['organization_id']);
        $table->dropUnique(['organization_id', 'priority']);
    });

    Schema::dropIfExists('sla_policies');
    }
};
