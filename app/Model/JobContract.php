<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class JobContract extends Model
{
    protected $table = 'job_contracts';

    public function getAllJobContract($from, $to, $location, $customer, $item)
    {
        $where = '';
        if($location){
            $where .= " AND jc.from_stk_loc = '$location' ";
        }
        if($customer){
            $where .= " AND jc.debtor_no = '$customer' ";
        }
        if($item){
            $where .= " AND jcd.stock_id = '$item' ";
        }
        if($from != NULL && $to != NULL ){
            $from = DbDateFormat($from);
            $to = DbDateFormat($to);
            $where .=" AND jc.contract_date BETWEEN '$from' AND '$to' ";
        }

//echo $where;
        $data = DB::select(DB::raw("SELECT jc.`job_contract_no`,jc.from_stk_loc,jcd.stock_id,jc.debtor_no,dm.name,jc.`reference`,jc.`total` as
      order_amount,COALESCE(ph.paid_amount,0) as paid_amount,jcd.ordered_quantity,COALESCE
      (invocie.invoiced_quantity,0) as invoiced_quantity,jc.contract_date  FROM
      `job_contracts` as jc
      LEFT JOIN debtors_master as dm
      ON dm.`debtor_no` = jc.`debtor_no`
      LEFT JOIN(SELECT SUM(`amount`) as paid_amount,`order_reference`
      FROM `payment_history` GROUP BY `order_reference`)ph
      ON ph.order_reference = jc.reference
      LEFT JOIN(SELECT job_contract_no, stock_id, SUM(quantity) as ordered_quantity FROM
      `job_contract_details` WHERE `trans_type` = 201 GROUP BY job_contract_no)
      jcd
      ON jcd.job_contract_no = jc.job_contract_no
      LEFT JOIN(SELECT order_no, SUM(qty) as invoiced_quantity FROM
      `stock_moves` WHERE `trans_type` = 202 GROUP BY order_no)
      invocie
      ON invocie.order_no = jc.job_contract_no
      WHERE jc.`trans_type` = 201
      $where
      ORDER BY jc.contract_date DESC"));

        return $data;

    }
}
