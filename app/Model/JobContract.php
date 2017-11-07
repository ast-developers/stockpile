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
        if ($location) {
            $where .= " AND jc.from_stk_loc = '$location' ";
        }
        if ($customer) {
            $where .= " AND jc.debtor_no = '$customer' ";
        }
        if ($item) {
            $where .= " AND jcd.description LIKE '%$item%' ";
        }
        if ($from != null && $to != null) {
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $where .= " AND jc.contract_date BETWEEN '$from' AND '$to' ";
        }

        $data = DB::select(DB::raw("SELECT jc.`job_contract_no`,jc.from_stk_loc,jcd.description,jc.debtor_no,dm.name,jc.`reference`,jc.`total` as
      order_amount,COALESCE(ph.paid_amount,0) as paid_amount,jcd.ordered_quantity,COALESCE
      (invocie.invoiced_quantity,0) as invoiced_quantity,jc.contract_date  FROM
      `job_contracts` as jc
      LEFT JOIN debtors_master as dm
      ON dm.`debtor_no` = jc.`debtor_no`
      LEFT JOIN(SELECT SUM(`amount`) as paid_amount,`contract_reference`
      FROM `job_contract_payment_history` GROUP BY `contract_reference`)ph
      ON ph.contract_reference = jc.reference
      LEFT JOIN(SELECT job_contract_no, description, SUM(quantity) as ordered_quantity FROM
      `job_contract_details` WHERE `trans_type` = 201 GROUP BY job_contract_no)
      jcd
      ON jcd.job_contract_no = jc.job_contract_no
      LEFT JOIN(SELECT contract_no, SUM(qty) as invoiced_quantity FROM
      `job_contract_moves` WHERE `trans_type` = 202 GROUP BY contract_no)
      invocie
      ON invocie.contract_no = jc.job_contract_no
      WHERE jc.`trans_type` = 201
      $where
      ORDER BY jc.contract_date DESC"));

        return $data;

    }


    function getJobContractByID($contractNo, $location = '')
    {
        $datas = array();
        $data  = DB::table('job_contract_details')
            ->where(['job_contract_no' => $contractNo])
            ->leftJoin('item_tax_types', 'item_tax_types.id', '=', 'job_contract_details.tax_type_id')
            ->select('job_contract_details.*', 'item_tax_types.tax_rate')
            ->orderBy('job_contract_details.quantity', 'DESC')
            ->get();
        //  d($data,1);
        foreach ($data as $key => $value) {
            //d($location,1);
            $datas[$key]['id']               = $value->id;
            $datas[$key]['job_contract_no']  = $value->job_contract_no;
            $datas[$key]['trans_type']       = $value->trans_type;
            $datas[$key]['tax_type_id']      = $value->tax_type_id;
            $datas[$key]['description']      = $value->description;
            $datas[$key]['unit_price']       = $value->unit_price;
            $datas[$key]['qty_sent']         = $value->qty_sent;
            $datas[$key]['quantity']         = $value->quantity;
            $datas[$key]['discount_percent'] = $value->discount_percent;
            $datas[$key]['tax_rate']         = $value->tax_rate;
        }

        return $datas;
    }


    public function calculateTaxRowRestItem($contractNo)
    {
        $tax_rows   = DB::select(DB::raw("select jcd.discount_percent, (jcd.quantity+COALESCE(jm.iqty,0)) as item_rest,jcd.unit_price,itt.tax_rate from (SELECT * FROM `job_contract_details` where job_contract_no = $contractNo)jcd left join (select stock_id, sum(qty) as iqty from job_contract_moves where contract_no = $contractNo group by stock_id)jm on jcd.stock_id = jm.stock_id left join item_tax_types as itt on itt.id = jcd.tax_type_id"));

        $tax_amount = [];
        $tax_rate   = [];
        $i          = 0;

        foreach ($tax_rows as $key => $value) {
            $sum = (($value->item_rest * $value->unit_price) - ($value->item_rest * $value->unit_price * $value->discount_percent / 100)) * $value->tax_rate / 100;
            if (isset($tax_amount[$value->tax_rate])) {
                $tax_amount[strval($value->tax_rate)] += $sum;
            } else {
                $tax_amount[strval($value->tax_rate)] = $sum;
            }
        }
        return $tax_amount;
    }


    public function getRestOrderItemsByOrderID($contractNo)
    {
        $data = DB::select(DB::raw("select contracts.from_stk_loc as location, jcd.*,(jcd.quantity+COALESCE(jm.iqty,0)) as item_rest,itt.tax_rate from (SELECT * FROM `job_contract_details` where job_contract_no = $contractNo)jcd
        left join (select stock_id, sum(qty) as iqty from job_contract_moves where contract_no = $contractNo group by stock_id)jm on jcd.stock_id = jm.stock_id
        left join item_tax_types as itt
        on itt.id = jcd.tax_type_id
        left join job_contracts as contracts
        on contracts.job_contract_no = jcd.job_contract_no"));
        return $data;
    }


    public function getInvoicedItemsQty($contractNo)
    {
        $data = DB::table('job_contract_moves')
            ->select(DB::raw('sum(qty) as total'),'stock_id','contract_no')
            ->where(['contract_no'=>$contractNo])
            ->groupBy(['stock_id','contract_no'])
            ->get();
        return $data;
    }


}
