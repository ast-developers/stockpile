<?php

use Illuminate\Database\Seeder;

class InvoicePaymentTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$invoicePaymentTerms = [
            array('terms' => 'Cash on deleivery', 'days_before_due' => 0, 'defaults' => 1),
            array('terms' => 'Net15', 'days_before_due' => 15, 'defaults' => 0),
            array('terms' => 'Net30', 'days_before_due' => 30, 'defaults' => 0),
        ];

        DB::table('invoice_payment_terms')->truncate();
		DB::table('invoice_payment_terms')->insert($invoicePaymentTerms);
    }
}