<?php

use Illuminate\Database\Seeder;

class CustomerBranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$customerBranch = array(
            array(
                'debtor_no'=>1,
                'br_name'=>'Mary Roe',
                'br_address'=>'',
                'br_contact'=>'',
                'billing_street'=>'MARY ROE',
                'billing_city'=>'MEGASYSTEMS INC',
                'billing_state'=>'TUCSON',
                'billing_zip_code'=>'Washington',
                'billing_country_id'=>'AZ 85705',
                'shipping_street'=>'USA',
                'shipping_city'=>'MEGASYSTEMS INC',
                'shipping_state'=>'TUCSON',
                'shipping_zip_code'=>'Washington',
                'shipping_country_id'=>'AZ 85705'
            ),
            array(
                'debtor_no'=>2,
                'br_name'=>'John Smith',
                'br_address'=>'',
                'br_contact'=>'',
                'billing_street'=>'JOHN SMITH',
                'billing_city'=>'300 BOYLSTON AVE E',
                'billing_state'=>'SEATTLE',
                'billing_zip_code'=>'Washington',
                'billing_country_id'=>'WA 98102',
                'shipping_street'=>'USA',
                'shipping_city'=>'300 BOYLSTON AVE E',
                'shipping_state'=>'SEATTLE',
                'shipping_zip_code'=>'Washington',
                'shipping_country_id'=>'WA 98102'
            ),
            array(
                'debtor_no'=>3,
                'br_name'=>'Kyla Olsen',
                'br_address'=>'',
                'br_contact'=>'',
                'billing_street'=>'Kyla Olsen',
                'billing_city'=>'Ap #651-8679 Sodales Av',
                'billing_state'=>'Tamuning',
                'billing_zip_code'=>'Tamuning',
                'billing_country_id'=>'PA 10855',
                'shipping_street'=>'TZ',
                'shipping_city'=>'Ap #651-8679 Sodales Av',
                'shipping_state'=>'Tamuning',
                'shipping_zip_code'=>'Tamuning',
                'shipping_country_id'=>'PA 10855'
            ),            
            array(
                'debtor_no'=>4,
                'br_name'=>'Cecilia Chapman',
                'br_address'=>'',
                'br_contact'=>'',
                'billing_street'=>'Cecilia Chapman',
                'billing_city'=>'711-2880 Nulla St',
                'billing_state'=>'Mankato',
                'billing_zip_code'=>'Mississippi',
                'billing_country_id'=>'96522',
                'shipping_street'=>'US',
                'shipping_city'=>'711-2880 Nulla St',
                'shipping_state'=>'Mankato',
                'shipping_zip_code'=>'Mississippi',
                'shipping_country_id'=>'96522'
            ),
            array(
                'debtor_no'=>5,
                'br_name'=>'Iris Watson',
                'br_address'=>'',
                'br_contact'=>'',
                'billing_street'=>'Iris Watson',
                'billing_city'=>'Fusce Rd',
                'billing_state'=>'Frederick',
                'billing_zip_code'=>'Nebraska',
                'billing_country_id'=>'20620',
                'shipping_street'=>'US',
                'shipping_city'=>'Fusce Rd',
                'shipping_state'=>'Frederick',
                'shipping_zip_code'=>'Nebraska',
                'shipping_country_id'=>'20620'
            )
        );
        DB::table('cust_branch')->truncate();
		DB::table('cust_branch')->insert($customerBranch);
    }
}