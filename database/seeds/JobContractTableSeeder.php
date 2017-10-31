<?php

use Illuminate\Database\Seeder;

class JobContractTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $JobContract = array(
            array(
                'trans_type'=>201,
                'debtor_no'=>1,
                'branch_id'=>1,
                'person_id'=>1,
                'reference'=>'JC-0001',
                'contract_reference_id'=>0,
                'contract_reference'=>NULL,
                'contract_date'=>date("Y-m-d", strtotime("-34 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>1840,
                'paid_amount'=>0,
                'payment_term'=>0
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>1,
                'branch_id'=>1,
                'person_id'=>1,
                'reference'=>'INV-0001',
                'contract_reference_id'=>1,
                'contract_reference'=>'JC-0001',
                'contract_date'=>date("Y-m-d", strtotime("-31 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>1840,
                'paid_amount'=>0,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>201,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'JC-0002',
                'contract_reference_id'=>0,
                'contract_reference'=>NULL,
                'contract_date'=>date("Y-m-d", strtotime("-29 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>9000,
                'paid_amount'=>0,
                'payment_term'=>0
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0002',
                'contract_reference_id'=>3,
                'contract_reference'=>'JC-0002',
                'contract_date'=>date("Y-m-d", strtotime("-26 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>9000,
                'paid_amount'=>5000,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>201,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'JC-0003',
                'contract_reference_id'=>0,
                'contract_reference'=>NULL,
                'contract_date'=>date("Y-m-d", strtotime("-27 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>245000,
                'paid_amount'=>0,
                'payment_term'=>0
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0003',
                'contract_reference_id'=>5,
                'contract_reference'=>'JC-0003',
                'contract_date'=>date("Y-m-d", strtotime("-21 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>33150,
                'paid_amount'=>0,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0004',
                'contract_reference_id'=>5,
                'contract_reference'=>'JC-0003',
                'contract_date'=>date("Y-m-d", strtotime("-14 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>39935,
                'paid_amount'=>1000,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0005',
                'contract_reference_id'=>5,
                'contract_reference'=>'JC-0003',
                'contract_date'=>date("Y-m-d", strtotime("-16 days")),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>27497.5,
                'paid_amount'=>27497.5,
                'payment_term'=>1
            ),
            array(
                'trans_type'=>202,
                'debtor_no'=>2,
                'branch_id'=>2,
                'person_id'=>1,
                'reference'=>'INV-0006',
                'contract_reference_id'=>5,
                'contract_reference'=>'JC-0003',
                'contract_date'=>date("Y-m-d"),
                'from_stk_loc'=>'PL',
                'payment_id'=>1,
                'total'=>920,
                'paid_amount'=>0,
                'payment_term'=>1
            )


        );

        DB::table('job_contracts')->truncate();
        DB::table('job_contracts')->insert($JobContract);
    }
}
