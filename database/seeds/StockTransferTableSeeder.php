<?php

use Illuminate\Database\Seeder;

class StockTransferTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$stockTransfer = array(
            array(
                'person_id'=>1,
                'source'=>'PL',
                'destination'=>'JA',
                'qty'=>10,
                'transfer_date'=>date("Y-m-d", strtotime("-3 days"))
            ),
            array(
                'person_id'=>1,
                'source'=>'JA',
                'destination'=>'PL',
                'qty'=>10,
                'transfer_date'=>date("Y-m-d", strtotime("-3 days"))
            )          
        );
        DB::table('stock_transfer')->truncate();
		DB::table('stock_transfer')->insert($stockTransfer);
    }
}