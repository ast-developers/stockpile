<?php

use Illuminate\Database\Seeder;

class PurchaseOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$purchaseOrder = array(
            array(
                'order_no'=>10,
                'supplier_id'=>2,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d", strtotime("-47 days")),
                'reference'=>'PO-0001',
                'into_stock_location'=>'PL',
                'total'=>207000,
                'tax_included'=>'yes'
            ),
            array(
                'order_no'=>11,
                'supplier_id'=>3,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d", strtotime("-2 days")),
                'reference'=>'PO-0002',
                'into_stock_location'=>'PL',
                'total'=>287500,
                'tax_included'=>'yes'
            ),
            array(
                'order_no'=>12,
                'supplier_id'=>4,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d", strtotime("-9 days")),
                'reference'=>'PO-0003',
                'into_stock_location'=>'JA',
                'total'=>172500,
                'tax_included'=>'yes'
            ),
            array(
                'order_no'=>13,
                'supplier_id'=>4,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d", strtotime("-414 days")),
                'reference'=>'PO-0004',
                'into_stock_location'=>'JA',
                'total'=>230000,
                'tax_included'=>'yes'
            ),
            array(
                'order_no'=>14,
                'supplier_id'=>3,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d", strtotime("-2 days")),
                'reference'=>'PO-0005',
                'into_stock_location'=>'JA',
                'total'=>115000,
                'tax_included'=>'yes'
            ), 
            array(
                'order_no'=>15,
                'supplier_id'=>5,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d", strtotime("-2 days")),
                'reference'=>'PO-0006',
                'into_stock_location'=>'JA',
                'total'=>57500,
                'tax_included'=>'yes'
            ),
            array(
                'order_no'=>16,
                'supplier_id'=>1,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d", strtotime("-2 days")),
                'reference'=>'PO-0007',
                'into_stock_location'=>'PL',
                'total'=>517500,
                'tax_included'=>'yes'
            ),
            array(
                'order_no'=>17,
                'supplier_id'=>5,
                'person_id'=>1,
                'ord_date'=>date("Y-m-d"),
                'reference'=>'PO-0008',
                'into_stock_location'=>'JA',
                'total'=>57500,
                'tax_included'=>'yes'
            ),

        );
        DB::table('purch_orders')->truncate();
		DB::table('purch_orders')->insert($purchaseOrder);
    }
}