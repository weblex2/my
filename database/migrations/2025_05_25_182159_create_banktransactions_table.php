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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->string('id')->unique()->before('account_number'); // oder `->after('email')`
            $table->string('account_number')->index();
            $table->date('booking_date');
            $table->date('value_date');
            $table->string('booking_text');
            $table->text('purpose');
            $table->string('counterparty');
            $table->string('counterparty_iban')->nullable();
            $table->string('counterparty_bic')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('info');
            $table->string('category');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
