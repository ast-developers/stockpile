<?php

use Illuminate\Database\Seeder;

class ShipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$shipment = array(
            array(
                'order_no'=>5,
                'trans_type'=>301,
                'status'=>0,
                'packed_date'=>date("Y-m-d", strtotime("-3 days")),
                'delivery_date'=>'0000-00-00'
            ),
            array(
                'order_no'=>5,
                'trans_type'=>301,
                'status'=>1,
                'packed_date'=>date("Y-m-d", strtotime("-3 days")),
                'delivery_date'=>date("Y-m-d", strtotime("-3 days"))
            )
        );
        DB::table('shipment')->truncate();
		DB::table('shipment')->insert($shipment);
    }
}