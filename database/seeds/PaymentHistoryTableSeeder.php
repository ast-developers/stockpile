<?php

use Illuminate\Database\Seeder;

class PaymentHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$paymentHistory = array(
            array(
                'payment_type_id'=>1,
                'order_reference'=>'SO-0003',
                'invoice_reference'=>'INV-0005',
                'payment_date'=>date('Y-m-d'),
                'amount'=>27497.5,
                'person_id'=>1,
                'customer_id'=>2
            ),
            array(
                'payment_type_id'=>1,
                'order_reference'=>'SO-0002',
                'invoice_reference'=>'INV-0002',
                'payment_date'=>date('Y-m-d'),
                'amount'=>5000,
                'person_id'=>1,
                'customer_id'=>2
            ),
            array(
                'payment_type_id'=>1,
                'order_reference'=>'SO-0003',
                'invoice_reference'=>'INV-0004',
                'payment_date'=>date('Y-m-d'),
                'amount'=>1000,
                'person_id'=>1,
                'customer_id'=>2
            )            
        );
        DB::table('payment_history')->truncate();
		DB::table('payment_history')->insert($paymentHistory);
    }
}