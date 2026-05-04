<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_group_id')->nullable()->after('country')->index();
            $table->dropIndex(['stripe_invoice_id']);
            $table->unique('stripe_invoice_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['stripe_invoice_id']);
            $table->index('stripe_invoice_id');
            $table->dropColumn('payment_group_id');
        });
    }
};
