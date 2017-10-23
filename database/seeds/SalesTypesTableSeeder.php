<?php

use Illuminate\Database\Seeder;

class SalesTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$salesTypes = [
            array('sales_type' => 'Retail', 'tax_included' => 1, 'defaults' => 1),
            array('sales_type' => 'Wholesale', 'tax_included' => 0, 'defaults' => 0),
        ];

        DB::table('sales_types')->truncate();
		DB::table('sales_types')->insert($salesTypes);
    }
}