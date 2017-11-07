<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class ContractSales extends Model
{
    protected $table = 'job_contracts';

    public function getContractSalseInvoiceByID($contract_no)
    {
        $data = DB::table('job_contract_details')
            ->where(['job_contract_no'=>$contract_no])
            ->leftJoin('item_tax_types', 'item_tax_types.id','=','job_contract_details.tax_type_id')
            ->select('job_contract_details.*','item_tax_types.tax_rate')
            ->get();
        return $data;
    }


    public function getAllSalseOrder($from, $to, $item, $customer, $location)
    {
        $conditions = array('job_contracts.trans_type'=>SALESINVOICE);
        $whereBetween = '';
        if($customer){
            $conditions['job_contracts.debtor_no'] = $customer;
        }
        if($location){
            $conditions['job_contracts.from_stk_loc'] = $location;
        }
        /*if($item){
            $conditions['job_contract_details.description'] = $item;
        }*/

        if($from !=NULL && $to != NULL){
            $from = DbDateFormat($from);
            $to = DbDateFormat($to);
            $data = $this->leftJoin('debtors_master', 'job_contracts.debtor_no', '=', 'debtors_master.debtor_no')
                ->leftJoin('location', 'job_contracts.from_stk_loc', '=', 'location.loc_code')
                ->select('job_contracts.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                ->leftJoin('job_contract_details', 'job_contracts.job_contract_no','=','job_contract_details.job_contract_no')
                ->where($conditions)
                ->where('job_contracts.contract_date','>=',$from)
                ->where('job_contracts.contract_date','<=',$to)
                ->where('job_contract_details.description','like','%'.$item.'%')
                ->orderBy('job_contracts.contract_date', 'desc')
                ->get();

        }else{

            $data = $this->leftJoin('debtors_master', 'job_contracts.debtor_no', '=', 'debtors_master.debtor_no')
                ->leftJoin('location', 'job_contracts.from_stk_loc', '=', 'location.loc_code')
                ->select('job_contracts.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                ->leftJoin('job_contract_details', 'job_contracts.job_contract_no','=','job_contract_details.job_contract_no')
                ->where($conditions)
                ->where('job_contract_details.description','like','%'.$item.'%')
                ->orderBy('job_contracts.contract_date', 'desc')
                ->get();
        }

        return $data;
    }
}
