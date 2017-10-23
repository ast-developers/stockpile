<?php

use Illuminate\Database\Seeder;

class PurchasePriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$purchasePrices = array(
            array(
                'stock_id'=>'APPLE',
                'price'=>100
            ),
            array(
                'stock_id'=>'HP',
                'price'=>80
            ),
            array(
                'stock_id'=>'LENEVO',
                'price'=>50
            ),
            array(
                'stock_id'=>'LG',
                'price'=>50
            ),
            array(
                'stock_id'=>'SAMSUNG',
                'price'=>50
            ),
            array(
                'stock_id'=>'SINGER',
                'price'=>50
            ),
            array(
                'stock_id'=>'SONY',
                'price'=>50
            ),
            array(
                'stock_id'=>'WALTON',
                'price'=>50
            )           
        );
        DB::table('purchase_prices')->truncate();
		DB::table('purchase_prices')->insert($purchasePrices);
    }
}