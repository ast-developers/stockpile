<?php

use Illuminate\Database\Seeder;

class PaymentTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$paymentTerms = [
            array('name' => 'Bank', 'defaults' => 1),
            array('name' => 'Cash', 'defaults' => 0)
        ];

        DB::table('payment_terms')->truncate();
		DB::table('payment_terms')->insert($paymentTerms);
    }
}