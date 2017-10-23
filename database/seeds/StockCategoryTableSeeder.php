<?php

use Illuminate\Database\Seeder;

class StockCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	
    $stockCategory = [
        ['description' => 'Default', 'dflt_units' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ['description' => 'Hardware', 'dflt_units' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ['description' => 'Health & Beauty', 'dflt_units' => 1, 'created_at' => date('Y-m-d H:i:s')]
    ];

        DB::table('stock_category')->truncate();
		DB::table('stock_category')->insert($stockCategory);
    }
}