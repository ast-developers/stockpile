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

        $data = DB::select(DB::raw("SELECT jc.`job_contract_no`,jc.from_stk_loc,jcd.stock_id,jc.debtor_no,dm.name,jc.`reference`,jc.`total` as
      order_amount,COALESCE(ph.paid_amount,0) as paid_amount,jcd.ordered_quantity,jc.contract_date  FROM
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
      WHERE jc.`trans_type` = 201
      $where
      ORDER BY jc.contract_date DESC"));

        return $data;

    }


    function getSalseOrderByID($contractNo,$location)
    {
        $datas = array();
        $data = DB::table('job_contract_details')
            ->where(['job_contract_no'=>$contractNo])
            ->leftJoin('item_tax_types', 'item_tax_types.id','=','job_contract_details.tax_type_id')
            ->select('job_contract_details.*','item_tax_types.tax_rate')
            ->orderBy('job_contract_details.quantity','DESC')
            ->get();
        //  d($data,1);
        foreach ($data as $key => $value) {
            //d($location,1);
            $datas[$key]['id'] = $value->id;
            $datas[$key]['job_contract_no'] = $value->job_contract_no;
            $datas[$key]['trans_type'] = $value->trans_type;
            $datas[$key]['tax_type_id'] = $value->tax_type_id;
            $datas[$key]['description'] = $value->description;
            $datas[$key]['unit_price'] = $value->unit_price;
            $datas[$key]['qty_sent'] = $value->qty_sent;
            $datas[$key]['quantity'] = $value->quantity;
            $datas[$key]['discount_percent'] = $value->discount_percent;
            $datas[$key]['tax_rate'] = $value->tax_rate;
        }

        return $datas;
    }



}
