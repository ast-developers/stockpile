<?php

use Illuminate\Database\Seeder;

class ItemCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$itemCode = array(
            array(
                'stock_id'=>'APPLE',
                'description'=>'Iphone 7+',
                'category_id'=>1,
                'item_image'=>'iphone.jpg'
            ),
            array(
                'stock_id'=>'HP',
                'description'=>'HP Pro Book',
                'category_id'=>1,
                'item_image'=>'hpprobook.jpg'
            ),
            array(
                'stock_id'=>'LENEVO',
                'description'=>'LED TV',
                'category_id'=>1,
                'item_image'=>'ledtv.jpg'
            ),
            array(
                'stock_id'=>'LG',
                'description'=>'LG Refrigeretor',
                'category_id'=>1,
                'item_image'=>'lgrefrigeretor.jpg'
            ),
            array(
                'stock_id'=>'SAMSUNG',
                'description'=>'Samsung G7',
                'category_id'=>1,
                'item_image'=>'samsung-galaxy7.jpg'
            ),
            array(
                'stock_id'=>'SINGER',
                'description'=>'Singer Refrigerator',
                'category_id'=>1,
                'item_image'=>'singer-refrideretor.jpg'
            ),
            array(
                'stock_id'=>'SONY',
                'description'=>'Sony experia 5',
                'category_id'=>1,
                'item_image'=>'sony-xperia5.jpg'
            ),
            array(
                'stock_id'=>'WALTON',
                'description'=>'Walton Primo GH',
                'category_id'=>1,
                'item_image'=>'walton-primo.jpg'
            )           
        );
        DB::table('item_code')->truncate();
		DB::table('item_code')->insert($itemCode);
    }
}