<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;


class ContractPayment extends Model
{
    protected $table = 'job_contract_payment_history';

    public function paymentFilter($from, $to, $customer, $payment_method)
    {
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);
        $conditions = array();

        if($customer){
            $conditions['customer_id'] = $customer;
        }
        if($payment_method){
            $conditions['payment_type_id'] = $payment_method;
        }

        $data = DB::table('job_contract_payment_history')
            ->leftjoin('debtors_master','debtors_master.debtor_no','=','job_contract_payment_history.customer_id')
            ->leftjoin('payment_terms','payment_terms.id','=','job_contract_payment_history.payment_type_id')
            ->leftjoin('job_contracts','job_contracts.reference','=','job_contract_payment_history.invoice_reference')
            ->select('job_contract_payment_history.*','debtors_master.name','payment_terms.name as pay_type','job_contracts.job_contract_no as invoice_id','job_contracts.contract_reference_id as contract_id')
            ->where('job_contract_payment_history.payment_date','>=',$from)
            ->where('job_contract_payment_history.payment_date','<=',$to)
            ->where($conditions)
            ->orderBy('job_contract_payment_history.payment_date','DESC')
            ->get();
        return $data;
    }

    public function updatePayment($reference,$amount)
    {
        $currentAmount = DB::table('job_contracts')->where('reference',$reference)->select('paid_amount')->first();
        $sum = ($currentAmount->paid_amount + $amount);
        DB::table('job_contracts')->where('reference',$reference)->update(['paid_amount' => $sum]);
        return true;
    }
}
