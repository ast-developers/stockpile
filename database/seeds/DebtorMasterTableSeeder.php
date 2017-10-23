<?php

use Illuminate\Database\Seeder;

class DebtorMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$debtorMaster = array(
            array(
                'name'=>'Mary Roe',
                'email'=>'maryroe@gmail.com',
                'password'=>'',
                'phone'=>'(257) 563-7401',
                'sales_type'=>0,
                'inactive'=>0
            ),
            array(
                'name'=>'John Smith',
                'email'=>'customer@techvill.net',
                'password'=>'$2y$10$NFl9z/cbBkX8q41bIkZbm.32OT/Ogp2fYKIZXifzgm2M6n1oG5/0C',
                'phone'=>'(372) 587-2335',
                'sales_type'=>0,
                'inactive'=>0
            ),
            array(
                'name'=>'Kyla Olsen',
                'email'=>'kyla.olsen@gmail.com',
                'password'=>'',
                'phone'=>'(654) 393-5734',
                'sales_type'=>0,
                'inactive'=>0
            ),

            array(
                'name'=>'Cecilia Chapman',
                'email'=>'cecilia@gmail.com',
                'password'=>'',
                'phone'=>'(257) 563-7401',
                'sales_type'=>0,
                'inactive'=>0
            ),
            array(
                'name'=>'Iris Watson',
                'email'=>'iris@yahoo.com',
                'password'=>'$2y$10$GwzEH2DV/98Fmt1s8bkk7.qWJsYZo9tW36c/cG/o9g/lGkrEp8fCC',
                'phone'=>'(372) 587-2335',
                'sales_type'=>0,
                'inactive'=>0
            )
        );
        DB::table('debtors_master')->truncate();
		DB::table('debtors_master')->insert($debtorMaster);
    }
}