<?php

use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$supplier = array(
            array(
                'supp_name'=>'Ina Moran',
                'email'=>'ina.morn@yahoo.com',
                'address'=>'Santa Rosa',
                'contact'=>'(684) 579-1879',
                'city'=>'Nunc Road',
                'state'=>'Lebanon',
                'zipCode'=>'KY 69409',
                'country'=>'Lebanon'
            ),
            array(
                'supp_name'=>'Hedy Greene',
                'email'=>'hedy@yahoo.com',
                'address'=>'Ap #696-3279 Viverra. Avenue',
                'contact'=>'(608) 265-2215',
                'city'=>'Latrobe',
                'state'=>'Lebanon',
                'zipCode'=>'DE 38100',
                'country'=>'Lebanon'
            ),
            array(
                'supp_name'=>'Melvin Porter',
                'email'=>'melvin@gmail.com',
                'address'=>'Curabitur Rd.',
                'contact'=>'(959) 119-8364',
                'city'=>'Bandera',
                'state'=>'South Dakota',
                'zipCode'=>'45149',
                'country'=>'USA'
            ),
            array(
                'supp_name'=>'Celeste Slater',
                'email'=>'celeste@yahoo.com',
                'address'=>'Ullamcorper. Street',
                'contact'=>'(786) 713-861',
                'city'=>'Roseville',
                'state'=>'New york',
                'zipCode'=>'NH 11523',
                'country'=>'United States'
            ),            
            array(
                'supp_name'=>'Theodore Lowe',
                'email'=>'lowe@yahoo.com',
                'address'=>'Ap #867-859 Sit Rd.',
                'contact'=>'(793) 151-623',
                'city'=>'Azusa',
                'state'=>'New York',
                'zipCode'=>'39531',
                'country'=>'United States'
            ),

        );
        DB::table('suppliers')->truncate();
		DB::table('suppliers')->insert($supplier);
    }
}