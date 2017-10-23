<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
	protected $table = 'sales_orders';

    public function getAllshipment()
    { 
          $data = DB::table('shipment')
                ->leftJoin('shipment_details', 'shipment.id', '=', 'shipment_details.shipment_id')
                ->leftJoin('sales_orders','sales_orders.order_no','=','shipment_details.order_no')
                ->leftJoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                ->select('shipment_details.shipment_id','sales_orders.reference','sales_orders.order_no as order_id','debtors_master.name','shipment.packed_date','shipment.status','debtors_master.debtor_no', DB::raw('sum(shipment_details.quantity) as total_shipment'))
                ->groupBy('shipment_details.shipment_id')
                ->orderBy('shipment.packed_date','DESC')
                ->get();
          return $data;
    }

    public function shipmentFiltering($from, $to, $customer, $status)
    { 
          $from = DbDateFormat($from);
          $to = DbDateFormat($to);
          $conditions = array();
          
          if($customer){
            $conditions['debtors_master.debtor_no'] = $customer;
          }
          if($status !='all'){
            $conditions['shipment.status'] = ($status=='packed') ? 0 : 1;
          }


          $data = DB::table('shipment')
                ->leftJoin('shipment_details', 'shipment.id', '=', 'shipment_details.shipment_id')
                ->leftJoin('sales_orders','sales_orders.order_no','=','shipment_details.order_no')
                ->leftJoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                ->select('shipment_details.shipment_id','sales_orders.reference','sales_orders.order_no as order_id','debtors_master.name','shipment.packed_date','shipment.status','debtors_master.debtor_no', DB::raw('sum(shipment_details.quantity) as total_shipment'))
                ->groupBy('shipment_details.shipment_id')
                ->where('shipment.packed_date','>=',$from)
                ->where('shipment.packed_date','<=',$to)
                ->where($conditions)
                ->orderBy('shipment.packed_date','DESC')
                ->get();
          return $data;
    }

    public function getShipmentItemByOrderID($orderNo)
    {
              $data = DB::table('sales_order_details')
                    ->where(['order_no'=>$orderNo])
                    ->leftJoin('item_code', 'sales_order_details.stock_id', '=', 'item_code.stock_id')
                    ->leftJoin('item_tax_types', 'item_tax_types.id','=','sales_order_details.tax_type_id')
                    ->select('sales_order_details.*', 'item_code.id as item_id','item_tax_types.tax_rate')
                    ->get();

          return $data;
    }


    public function getInvoicedItemsByOrderID($orderNo)
    {
      $data = DB::select(DB::raw("select so.*,COALESCE(sm.iqty,0) as item_invoiced,ic.id as item_id,itt.tax_rate from (SELECT * FROM `sales_order_details` where order_no = $orderNo)so left join (select stock_id, sum(qty) as iqty from stock_moves where order_no = $orderNo group by stock_id)sm on so.stock_id = sm.stock_id left join item_code as ic on ic.stock_id = so.stock_id left join item_tax_types as itt on itt.id = so.tax_type_id"));
      return $data;
    }

    public function calculateTaxRow($orderNo){
    $tax_rows = DB::select(DB::raw("select so.*,COALESCE(sm.iqty,0) as item_invoiced,COALESCE(sis.sqty,0) as item_shipted,ic.id as item_id,itt.tax_rate from (SELECT * FROM `sales_order_details` where order_no = $orderNo)so left join (select stock_id, sum(qty) as iqty from stock_moves where order_no = $orderNo group by stock_id)sm on so.stock_id = sm.stock_id left join item_code as ic on ic.stock_id = so.stock_id left join item_tax_types as itt on itt.id = so.tax_type_id left join (select stock_id, sum(quantity) as sqty from shipment_details where order_no = $orderNo group by stock_id)sis on so.stock_id = sis.stock_id"));      
   // d($tax_rows,1);
    $data = array();
      foreach($tax_rows as $k=>$result){
        $data[$k]['tax_amount'] = (((int)abs($result->item_invoiced)-$result->item_shipted)*$result->unit_price*$result->tax_rate)/100;
        $data[$k]['tax_rate']   = $result->tax_rate;
      }

    $tax_amount = [];
    $tax_rate   =[];
    $i=0;
    foreach ($data as $key => $value) {
       if(isset($tax_amount[$value['tax_rate']])){
         $tax_amount[strval($value['tax_rate'])] +=$value['tax_amount'];
       }else{
         $tax_amount[strval($value['tax_rate'])] =$value['tax_amount'];
       }

    }

    return $tax_amount;
  }

  public function getTotalShipmentByOrderNo($orderNo){

 $total = DB::table('shipment')
            ->leftJoin('shipment_details','shipment.id','=','shipment_details.shipment_id')
            ->where(['shipment.order_no'=> $orderNo])
            ->groupBy('shipment_details.order_no')
            ->sum('shipment_details.quantity');
 //d($total,1);
    return $total;
    
  }

  public function getTotalInvoicedByOrderNo($orderNo){
    $total = DB::table('stock_moves')->where('order_no',$orderNo)->groupBy('order_no')->sum('qty');
    return $total;  
  }

  public function getAvailableItemsByOrderID($orderNo){
    $items = DB::select(DB::raw("select so.*,COALESCE(sm.iqty,0) as item_invoiced,COALESCE(sis.sqty,0) as item_shipted,ic.id as item_id,itt.tax_rate from (SELECT * FROM `sales_order_details` where order_no = $orderNo)so left join (select stock_id, sum(qty) as iqty from stock_moves where order_no = $orderNo group by stock_id)sm on so.stock_id = sm.stock_id left join item_code as ic on ic.stock_id = so.stock_id left join item_tax_types as itt on itt.id = so.tax_type_id left join (select stock_id, sum(quantity) as sqty from shipment_details where order_no = $orderNo group by stock_id)sis on so.stock_id = sis.stock_id"));      
   return $items;
 }

    public function calculateTaxForDetail($shipment_id){
    $tax_amount = [];
    $tax_rate   =[];
    $price      = 0;
    $discount   = 0;
    $discountPriceAmount = 0;
    $i=0;
    $tax = DB::table('shipment_details')
         ->where('shipment_details.shipment_id',$shipment_id)
         ->leftjoin('item_tax_types','item_tax_types.id','=','shipment_details.tax_type_id')
         ->select('shipment_details.*','item_tax_types.tax_rate')
         ->get();
      
    $data = array();
      foreach($tax as $k=>$result){
        $price = ($result->quantity*$result->unit_price);
        $discount =  ($result->discount_percent*$price)/100;
        $discountPriceAmount = ($price-$discount);

        $data[$k]['tax_amount'] = ($discountPriceAmount*$result->tax_rate)/100;
        $data[$k]['tax_rate']   = $result->tax_rate;
      }
    foreach ($data as $key => $value) {
       if(isset($tax_amount[$value['tax_rate']])){
         $tax_amount[strval($value['tax_rate'])] +=$value['tax_amount'];
       }else{
         $tax_amount[strval($value['tax_rate'])] =$value['tax_amount'];
       }
    }
   // d($tax_amount,1);
    return $tax_amount;
  }

  public function getAllStockTransferByUserId($from, $to, $source, $destination, $id){
    $from = DbDateFormat($from);
    $to = DbDateFormat($to);

    if($source=='all' && $destination=='all'){
        $data = DB::table('stock_transfer')
                  ->where('person_id',$id)
                  ->whereDate('transfer_date','>=', $from)
                  ->whereDate('transfer_date','<=', $to)
                  ->orderBy('transfer_date','DESC')
                  ->get(); 
        }else if( $source !='all' && $destination !='all' ){
        $data = DB::table('stock_transfer')
                  ->where(['person_id'=>$id,'source'=>$source,'destination'=>$destination])
                  ->whereDate('transfer_date','>=', $from)
                  ->whereDate('transfer_date','<=', $to)
                  ->orderBy('transfer_date','DESC')
                  ->get(); 
        }else if( $source =='all' && $destination !='all' ){
        $data = DB::table('stock_transfer')
                  ->where(['person_id'=>$id,'destination'=>$destination])
                  ->whereDate('transfer_date','>=', $from)
                  ->whereDate('transfer_date','<=', $to)
                  ->orderBy('transfer_date','DESC')
                  ->get(); 
        }else if( $source !='all' && $destination =='all' ){
        $data = DB::table('stock_transfer')
                  ->where(['person_id'=>$id,'source'=>$source])
                  ->whereDate('transfer_date','>=', $from)
                  ->whereDate('transfer_date','<=', $to)
                  ->orderBy('transfer_date','DESC')
                  ->get(); 
            }

    return $data;
  }

}
