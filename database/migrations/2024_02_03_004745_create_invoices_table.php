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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_service_id')->constrained();
            $table->foreignId("user_id")->constrained();
            $table->string('billing_to');
            $table->string('checkout_link')->nullable();
            $table->string('external_id')->nullable();
            $table->string('address');
            $table->char('invoice_code', 7);
            $table->string('status');
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
