<?php

use Illuminate\Database\Seeder;

class StockMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$stockMaster = array(
            array(
                'stock_id'=>'APPLE',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'Iphone 7+'
            ),
            array(
                'stock_id'=>'HP',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'HP Pro Book'
            ),
            array(
                'stock_id'=>'LENEVO',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'LED TV'
            ),
            array(
                'stock_id'=>'LG',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'LG Refrigeretor' 
            ),
            array(
                'stock_id'=>'SAMSUNG',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'Samsung G7'
            ),
            array(
                'stock_id'=>'SINGER',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'Singer Refrigerator'
            ),
            array(
                'stock_id'=>'SONY',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'Sony experia 5'   
            ),
            array(
                'stock_id'=>'WALTON',
                'category_id'=>1,
                'tax_type_id'=>2,
                'units'=>'Each',
                'description'=>'Walton Primo GH'
            )           
        );
        DB::table('stock_master')->truncate();
		DB::table('stock_master')->insert($stockMaster);
    }
}