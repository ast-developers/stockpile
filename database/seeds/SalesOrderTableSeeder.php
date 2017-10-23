<?php

use Illuminate\Database\Seeder;

class SalesOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$SalesOrder = array(
            array(
                'trans_type'=>201,
                'debtor_no'=>1,
                'branch_id'=>1,
                'person_id'=>1,
                'reference'=>'SO-0001',
                'order_reference_id'=>0,
                'order_reference'=>NULL,
                'ord_date'=>date("Y-m-d", strtotime("-34 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>1840,
                'paid_amount'=>0,
                'payment_term'=>0
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>1,
                'branch_id'=>1,
                'person_id'=>1,
                'reference'=>'INV-0001',
                'order_reference_id'=>1,
                'order_reference'=>'SO-0001',
                'ord_date'=>date("Y-m-d", strtotime("-31 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>1840,
                'paid_amount'=>0,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>201,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'SO-0002',
                'order_reference_id'=>0,
                'order_reference'=>NULL,
                'ord_date'=>date("Y-m-d", strtotime("-29 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>9000,
                'paid_amount'=>0,
                'payment_term'=>0
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0002',
                'order_reference_id'=>3,
                'order_reference'=>'SO-0002',
                'ord_date'=>date("Y-m-d", strtotime("-26 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>9000,
                'paid_amount'=>5000,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>201,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'SO-0003',
                'order_reference_id'=>0,
                'order_reference'=>NULL,
                'ord_date'=>date("Y-m-d", strtotime("-27 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>245000,
                'paid_amount'=>0,
                'payment_term'=>0
            ), 
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0003',
                'order_reference_id'=>5,
                'order_reference'=>'SO-0003',
                'ord_date'=>date("Y-m-d", strtotime("-21 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>33150,
                'paid_amount'=>0,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0004',
                'order_reference_id'=>5,
                'order_reference'=>'SO-0003',
                'ord_date'=>date("Y-m-d", strtotime("-14 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>39935,
                'paid_amount'=>1000,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0005',
                'order_reference_id'=>5,
                'order_reference'=>'SO-0003',
                'ord_date'=>date("Y-m-d", strtotime("-16 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>27497.5,
                'paid_amount'=>27497.5,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0006',
                'order_reference_id'=>5,
                'order_reference'=>'SO-0003',
                'ord_date'=>date("Y-m-d"),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>920,
                'paid_amount'=>0,
                'payment_term'=>1
            )


        );

        DB::table('sales_orders')->truncate();
		DB::table('sales_orders')->insert($SalesOrder);
    }
}