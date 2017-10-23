<?php

use Illuminate\Database\Seeder;

class SalesOrderDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$salesOrderDetails = array(
            array(
                'order_no'=>1,
                'trans_type'=>201,
                'stock_id'=>'APPLE',
                'tax_type_id'=>2,
                'description'=>'Iphone 7+',
                'unit_price'=>160,
                'qty_sent'=>0,
                'quantity'=>10,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>2,
                'trans_type'=>202,
                'stock_id'=>'APPLE',
                'tax_type_id'=>2,
                'description'=>'Iphone 7+',
                'unit_price'=>160,
                'qty_sent'=>10,
                'quantity'=>10,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ), 
            array(
                'order_no'=>3,
                'trans_type'=>201,
                'stock_id'=>'SAMSUNG',
                'tax_type_id'=>1,
                'description'=>'Samsung G7',
                'unit_price'=>90,
                'qty_sent'=>0,
                'quantity'=>100,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>4,
                'trans_type'=>202,
                'stock_id'=>'SAMSUNG',
                'tax_type_id'=>1,
                'description'=>'Samsung G7',
                'unit_price'=>90,
                'qty_sent'=>100,
                'quantity'=>100,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>5,
                'trans_type'=>201,
                'stock_id'=>'APPLE',
                'tax_type_id'=>2,
                'description'=>'Iphone 7+',
                'unit_price'=>160,
                'qty_sent'=>1000,
                'quantity'=>1000,
                'shipment_qty'=>20,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>5,
                'trans_type'=>201,
                'stock_id'=>'WALTON',
                'tax_type_id'=>4,
                'description'=>'Walton Primo GH',
                'unit_price'=>85,
                'qty_sent'=>1000,
                'quantity'=>1000,
                'shipment_qty'=>20,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>6,
                'trans_type'=>202,
                'stock_id'=>'APPLE',
                'tax_type_id'=>2,
                'description'=>'Iphone 7+',
                'unit_price'=>160,
                'qty_sent'=>20,
                'quantity'=>20,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),                                    
            array(
                'order_no'=>6,
                'trans_type'=>202,
                'stock_id'=>'WALTON',
                'tax_type_id'=>4,
                'description'=>'Walton Primo GH',
                'unit_price'=>85,
                'qty_sent'=>20,
                'quantity'=>20,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>7,
                'trans_type'=>202,
                'stock_id'=>'APPLE',
                'tax_type_id'=>2,
                'description'=>'Iphone 7+',
                'unit_price'=>160,
                'qty_sent'=>50,
                'quantity'=>50,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>7,
                'trans_type'=>202,
                'stock_id'=>'WALTON',
                'tax_type_id'=>4,
                'description'=>'Walton Primo GH',
                'unit_price'=>85,
                'qty_sent'=>50,
                'quantity'=>50,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>8,
                'trans_type'=>202,
                'stock_id'=>'APPLE',
                'tax_type_id'=>2,
                'description'=>'Iphone 7+',
                'unit_price'=>160,
                'qty_sent'=>5,
                'quantity'=>5,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>8,
                'trans_type'=>202,
                'stock_id'=>'WALTON',
                'tax_type_id'=>4,
                'description'=>'Walton Primo GH',
                'unit_price'=>85,
                'qty_sent'=>5,
                'quantity'=>5,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),
            array(
                'order_no'=>9,
                'trans_type'=>202,
                'stock_id'=>'APPLE',
                'tax_type_id'=>2,
                'description'=>'Iphone 7+',
                'unit_price'=>160,
                'qty_sent'=>5,
                'quantity'=>5,
                'shipment_qty'=>0,
                'discount_percent'=>0
            ),            
        );
        DB::table('sales_order_details')->truncate();
		DB::table('sales_order_details')->insert($salesOrderDetails);
    }
}