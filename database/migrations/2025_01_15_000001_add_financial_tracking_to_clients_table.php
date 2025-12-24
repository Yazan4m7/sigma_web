<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinancialTrackingToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Income/Outcome percentage (0-100)
            $table->decimal('income_percentage', 5, 2)->default(0)->after('id')->comment('Percentage of income vs total transactions');
            $table->decimal('outcome_percentage', 5, 2)->default(0)->after('income_percentage')->comment('Percentage of outcome vs total transactions');
            
            // Approximate income (calculated field)
            $table->decimal('approx_income', 12, 2)->default(0)->after('outcome_percentage')->comment('Approximate monthly income from this client');
            
            // Risk assessment
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low')->after('approx_income')->comment('Payment risk level');
            $table->integer('avg_payment_days')->default(0)->after('risk_level')->comment('Average days to payment');
            $table->integer('current_overdue_days')->default(0)->after('avg_payment_days')->comment('Current overdue days');
            $table->decimal('outstanding_balance', 12, 2)->default(0)->after('current_overdue_days')->comment('Current outstanding balance');
            
            // Timestamps for tracking
            $table->timestamp('last_financial_update')->nullable()->after('outstanding_balance')->comment('Last time financial metrics were calculated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'income_percentage',
                'outcome_percentage',
                'approx_income',
                'risk_level',
                'avg_payment_days',
                'current_overdue_days',
                'outstanding_balance',
                'last_financial_update'
            ]);
        });
    }
}
