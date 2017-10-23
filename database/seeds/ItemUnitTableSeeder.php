<?php

use Illuminate\Database\Seeder;

class ItemUnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$itemUnit = [
            'abbr' => 'each',
            'name' => 'Each',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        DB::table('item_unit')->truncate();
		DB::table('item_unit')->insert($itemUnit);
    }
}