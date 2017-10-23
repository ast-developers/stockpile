<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
	protected $table = 'sales_orders';

    public function getAllSalseOrder($from, $to, $item, $customer, $location)
    { 
        $conditions = array('sales_orders.trans_type'=>SALESINVOICE);
        $whereBetween = '';
        if($customer){
            $conditions['sales_orders.debtor_no'] = $customer;
        }
        if($location){
            $conditions['sales_orders.from_stk_loc'] = $location;
        }
        if($item){
            $conditions['sales_order_details.stock_id'] = $item;
        }

        if($from !=NULL && $to != NULL){
            $from = DbDateFormat($from);
            $to = DbDateFormat($to);
        $data = $this->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('location', 'sales_orders.from_stk_loc', '=', 'location.loc_code')
                    ->select('sales_orders.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                    ->leftJoin('sales_order_details', 'sales_orders.order_no','=','sales_order_details.order_no')
                    ->where($conditions)
                    ->where('sales_orders.ord_date','>=',$from)
                    ->where('sales_orders.ord_date','<=',$to)
                    ->orderBy('sales_orders.ord_date', 'desc')
                    ->get();

        }else{

        $data = $this->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('location', 'sales_orders.from_stk_loc', '=', 'location.loc_code')
                    ->select('sales_orders.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                    ->leftJoin('sales_order_details', 'sales_orders.order_no','=','sales_order_details.order_no')
                    ->where($conditions)
                    ->orderBy('sales_orders.ord_date', 'desc')
                    ->get();
        }
       
        return $data;
    }


    public function getSalseInvoiceByID($order_no)
    {
              $data = DB::table('sales_order_details')
                    ->where(['order_no'=>$order_no])
                    ->leftJoin('item_code', 'sales_order_details.stock_id', '=', 'item_code.stock_id')
                    ->leftJoin('item_tax_types', 'item_tax_types.id','=','sales_order_details.tax_type_id')
                    ->select('sales_order_details.*', 'item_code.id as item_id','item_tax_types.tax_rate')
                    ->get();
          return $data;
    }

    public function getSalseInvoicePdf($id)
    { 
        return $this->where(['sales_orders.order_no'=>$id])
                    ->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('sales_order_details', 'sales_orders.order_no', '=', 'sales_order_details.order_no')
                    ->select('sales_orders.*', 'debtors_master.*')
                    ->first();
    }

    public function stockValidate($loc,$id)
    {
        $data = DB::table('stock_moves')
                     ->select(DB::raw('sum(qty) as total'))
                     ->where(['stock_id'=>$id, 'loc_code'=>$loc])
                     ->groupBy('loc_code')
                     ->first();
        
        if(count($data)>0){
            if($data->total <= 0){
               $total = 0; 
            }else{
            $total = $data->total;
        }
        }else{
         $total = 0;   
        }
         return $total;
    }

        public function calculateTaxRow($order_no){
        
        $tax_rows = DB::table('sales_order_details')
                ->join('item_tax_types', 'item_tax_types.id', '=', 'sales_order_details.tax_type_id')
                ->select(DB::raw('((sales_order_details.quantity*sales_order_details.unit_price-sales_order_details.quantity*sales_order_details.unit_price*sales_order_details.discount_percent/100)*item_tax_types.tax_rate)/100 AS tax_amount,item_tax_types.tax_rate'))
                ->where('order_no', $order_no)
                ->get();

       // d($tax_rows,1);

        $tax_amount = [];
        $tax_rate   =[];
        $i=0;
        foreach ($tax_rows as $key => $value) {
           if(isset($tax_amount[$value->tax_rate])){
                $tax_amount[strval($value->tax_rate)] +=$value->tax_amount;
           }else{
             $tax_amount[strval($value->tax_rate)] =$value->tax_amount;
           }
        }
        return $tax_amount;
    }
    public function getAllSalseInvoiceByUserId($from, $to, $customer, $location, $id)
    { 
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);

        if($customer =='all' && $location =='all'){
        $data = $this->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('location', 'sales_orders.from_stk_loc', '=', 'location.loc_code')
                    ->select('sales_orders.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                    ->where(['sales_orders.trans_type'=>SALESINVOICE,'sales_orders.person_id'=>$id])
                    ->whereDate('sales_orders.ord_date','>=', $from)
                    ->whereDate('sales_orders.ord_date','<=', $to)
                    ->orderBy('sales_orders.ord_date', 'desc')
                    ->get();
        }else if($customer !='all' && $location =='all'){
        $data = $this->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('location', 'sales_orders.from_stk_loc', '=', 'location.loc_code')
                    ->select('sales_orders.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                    ->where(['sales_orders.trans_type'=>SALESINVOICE,'sales_orders.person_id'=>$id,'sales_orders.debtor_no'=>$customer])
                    ->whereDate('sales_orders.ord_date','>=', $from)
                    ->whereDate('sales_orders.ord_date','<=', $to)
                    ->orderBy('sales_orders.ord_date', 'desc')
                    ->get();
        }else if($customer =='all' && $location !='all'){
        $data = $this->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('location', 'sales_orders.from_stk_loc', '=', 'location.loc_code')
                    ->select('sales_orders.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                    ->where(['sales_orders.trans_type'=>SALESINVOICE,'sales_orders.person_id'=>$id,'sales_orders.from_stk_loc'=>$location])
                    ->whereDate('sales_orders.ord_date','>=', $from)
                    ->whereDate('sales_orders.ord_date','<=', $to)
                    ->orderBy('sales_orders.ord_date', 'desc')
                    ->get();
        }else if($customer !='all' && $location !='all'){
        $data = $this->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('location', 'sales_orders.from_stk_loc', '=', 'location.loc_code')
                    ->select('sales_orders.*', 'debtors_master.name as cus_name','location.location_name as loc_name')
                    ->where(['sales_orders.trans_type'=>SALESINVOICE,'sales_orders.person_id'=>$id,'sales_orders.from_stk_loc'=>$location,'sales_orders.debtor_no'=>$customer])
                    ->whereDate('sales_orders.ord_date','>=', $from)
                    ->whereDate('sales_orders.ord_date','<=', $to)
                    ->orderBy('sales_orders.ord_date', 'desc')
                    ->get();
        }
        return $data;

    }

        public function getSaleYears(){
        $data = DB::select("SELECT DISTINCT YEAR(ord_date) as year FROM sales_orders WHERE trans_type = 202  ORDER BY ord_date DESC");
        return $data;
    }

}
