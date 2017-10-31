<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EmailController;
use App\Model\Shipment;
use App\Http\Requests;
use App\Model\Orders;
use DB;
use PDF;
use Session;

class ShipmentController extends Controller
{
    public function __construct(Shipment $shipment,EmailController $email, Orders $orders){
     
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
        $this->shipment = $shipment;
        $this->email = $email;
        $this->order = $orders;

    }

    public function index()
    {
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'shipment/list';
        $data['shipmentList'] = $this->shipment->getAllshipment();
        return view('admin.shipment.shipmentList', $data);
    }

    /**
    *Shipment filtering
    */

    public function shipmentFiltering()
    {
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'shipment/list';
        $data['statuses'] = array('packed'=>'Packed' ,'delivered'=>'Delivered');
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : NULL;
        $data['status'] = $status = isset($_GET['status']) ? $_GET['status'] : 'all';
        
        $data['customerList'] = DB::table('debtors_master')->select('debtor_no','name')->where(['inactive'=>0])->get();

        $fromDate = DB::table('shipment')->select('packed_date')->orderBy('packed_date','asc')->first();
        
        if(isset($_GET['from'])){
            $data['from'] = $from = $_GET['from'];
        }else{
           $data['from'] = $from = formatDate(date("d-m-Y", strtotime($fromDate->packed_date))); 
        }
        
        if(isset($_GET['to'])){
            $data['to'] = $to = $_GET['to'];
        }else{
            $data['to'] = $to = formatDate(date('d-m-Y'));
        }


        $data['shipmentList'] = $this->shipment->shipmentFiltering($from, $to, $customer, $status);
        return view('admin.shipment.shipmentFilterList', $data);
    }
    /**
     * Show the form for creating a new shipment.
     */

    public function createShipment($orderNo)
    {
        //------------Insertion in stock table---------------

        //------Insert the stock record, like when we create it on auto invoice create----

        //----------Check whether the record is instered before or not----------
        $stockMovesTableEntry = DB::table('stock_moves')->where('order_no', '=', $orderNo)->get();

        //Insert the record if not inserted before, at the time of invoice generation
        if(!$stockMovesTableEntry){
            $invoiceInfos = $this->order->getRestOrderItemsByOrderID($orderNo);
            $orderInfo    = DB::table('sales_orders')->where('order_no', '=', $orderNo)->first();
            $userId       = \Auth::user()->id;

            foreach ($invoiceInfos as $i => $invoiceInfo) {
                // create stockMove
                $stockMove['stock_id']                 = $invoiceInfo->stock_id;
                $stockMove['order_no']                 = $orderNo;
                $stockMove['loc_code']                 = $orderInfo->from_stk_loc;
                $stockMove['tran_date']                = date('Y-m-d');
                $stockMove['person_id']                = $userId;
                $stockMove['reference']                = 'store_out_' . $orderNo;
                $stockMove['transaction_reference_id'] = $orderNo;
                $stockMove['qty']                      = '-' . $invoiceInfo->item_rest;
                $stockMove['price']                    = $invoiceInfo->unit_price;
                $stockMove['trans_type']               = SALESINVOICE;
                $stockMove['order_reference']          = $orderInfo->reference;

                DB::table('stock_moves')->insertGetId($stockMove);
            }
        }

        //------------Insertion in stock table Ends---------------

        $data['menu'] = 'sales';
        $data['sub_menu'] = 'shipment/list';
        $data['taxType'] = $this->shipment->calculateTaxRow($orderNo);
        $data['orderInfo'] = DB::table('sales_orders')
                            ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                            ->where('order_no', '=', $orderNo)
                            ->first();
        $data['invoicedItems'] = DB::table('stock_moves')->where(['order_no'=>$orderNo,'trans_type'=>SALESINVOICE])->groupBy('stock_id')->lists('stock_id');
        $data['shipmentItem'] = $this->shipment->getInvoicedItemsByOrderID($orderNo);

        $invoicedTotal = $this->shipment->getTotalInvoicedByOrderNo($orderNo);
        $shipmentTotal = $this->shipment->getTotalShipmentByOrderNo($orderNo);
        $shipment = (int)abs($invoicedTotal)-$shipmentTotal;
        
        $data['shipmentStatus'] = ($shipment>0) ? 'available' : 'notAvailable';
        
        return view('admin.shipment.createShipment', $data);
    }

    /**
     * Update the specified resource in storage.
     **/
    public function storeShipment(Request $request)
    {

        $this->validate($request, [
            'packed_date' => 'required',
        ]);
        
        $orderNo = $request->order_no;
        $reference = $request->reference;
        
        $itemQty = $request->item_quantity;
        $stockIds = $request->stock_id;
        $itemIds = $request->item_id;
        $taxIds  = $request->tax_id;
        $discount  = $request->discount;
        $unitPrice  = $request->unit_price;

        // update sales_order table
        
        $shipmentData['order_no'] =  $orderNo;

        $shipmentData['trans_type'] =  DELIVERYORDER;
        $shipmentData['packed_date'] = DbDateFormat($request->packed_date);
        $shipmentData['comments'] = $request->comments;        
        $shipmentId = DB::table('shipment')->insertGetId($shipmentData);
        
        DB::table('sales_orders')->where(['order_no'=>$orderNo,'reference'=>$reference])->update(['version'=>1]);
        
        $shipmentQty = array();
        $shipmentHistory = array();
       foreach ($stockIds as $key => $stockId) {

           $shipmentQty[$key]['stock_id'] = $stockId;
           $shipmentQty[$key]['shipment_qty'] = $itemQty[$key];
           $shipmentHistory[$key]['shipment_id'] =  $shipmentId;
           $shipmentHistory[$key]['order_no'] =  $orderNo;
           $shipmentHistory[$key]['stock_id'] =  $stockId;
           $shipmentHistory[$key]['tax_type_id'] =  $taxIds[$key];
           $shipmentHistory[$key]['discount_percent'] =  $discount[$key];
           $shipmentHistory[$key]['unit_price'] =  $unitPrice[$key];
           $shipmentHistory[$key]['quantity'] =  $itemQty[$key];

       }

       if(count($shipmentQty)>0){
        foreach($shipmentQty as $itemInfo){
            $previousShipmentQty = DB::table('sales_order_details')->where(['order_no'=>$orderNo,'trans_type'=>SALESORDER,'stock_id'=>$itemInfo['stock_id']])->sum('shipment_qty');
            $shipmentQuantity = ($previousShipmentQty + $itemInfo['shipment_qty']); 
            DB::table('sales_order_details')->where(['order_no'=>$orderNo,'trans_type'=>SALESORDER,'stock_id'=>$itemInfo['stock_id']])->update(['shipment_qty'=>$shipmentQuantity]); 
        }
       }

       if(count($shipmentHistory)>0){
        for ($i=0; $i < count($shipmentHistory); $i++) {
            DB::table('shipment_details')->insert($shipmentHistory[$i]); 
        }
       }

        \Session::flash('success',trans('message.success.save_success'));
         return redirect()->intended('shipment/view-details/'.$orderNo.'/'.$shipmentId);
    }

    public function storeAutoShipment($orderNo){
        $shipmentItem = $this->shipment->getAvailableItemsByOrderID($orderNo);
        
        $shipmentData['order_no'] =  $orderNo;
        $shipmentData['trans_type'] =  DELIVERYORDER;
        $shipmentData['comments'] = 'Auto shipment';
        $shipmentData['packed_date'] = date('Y-m-d');        
        $shipmentId = DB::table('shipment')->insertGetId($shipmentData);
      
        DB::table('sales_orders')->where(['order_no'=>$orderNo])->update(['version'=>1]);
        $shipmentQty = array();
        $shipmentHistory = array();
        foreach ($shipmentItem as $key => $item) {
            $shipQty = ((int)abs($item->item_invoiced)-$item->item_shipted);
            if(!$shipQty){
                $shipQty = $item->quantity;
            }

           $shipmentQty[$key]['stock_id'] = $item->stock_id;
           $shipmentQty[$key]['shipment_qty'] = $shipQty;

           // Array for shipmentHistory
           $shipmentHistory[$key]['shipment_id'] =  $shipmentId;
           $shipmentHistory[$key]['order_no'] =  $orderNo;
           $shipmentHistory[$key]['stock_id'] =  $item->stock_id;
           $shipmentHistory[$key]['tax_type_id'] =  $item->tax_type_id;
           $shipmentHistory[$key]['discount_percent'] =  $item->discount_percent;
           $shipmentHistory[$key]['unit_price'] =  $item->unit_price;
           $shipmentHistory[$key]['quantity'] =  $shipQty;

       }

       if(count($shipmentQty)>0){
        foreach($shipmentQty as $itemInfo){
            $previousShipmentQty = DB::table('sales_order_details')->where(['order_no'=>$orderNo,'trans_type'=>SALESORDER,'stock_id'=>$itemInfo['stock_id']])->sum('shipment_qty');
            $shipmentQuantity = ($previousShipmentQty + $itemInfo['shipment_qty']); 
            DB::table('sales_order_details')->where(['order_no'=>$orderNo,'trans_type'=>SALESORDER,'stock_id'=>$itemInfo['stock_id']])->update(['shipment_qty'=>$shipmentQuantity]); 
        }
       }

       if(count($shipmentHistory)>0){
        for ($i=0; $i < count($shipmentHistory); $i++) {
            DB::table('shipment_details')->insert($shipmentHistory[$i]); 
        }
       }

        \Session::flash('success',trans('message.success.save_success'));
        return redirect()->intended('shipment/view-details/'.$orderNo.'/'.$shipmentId);
    }

    public function StatusChange(Request $request){
      $data = array();
      $data['status_no'] = 0; 
     
      $shipment_id = $request['id'];
      $updated = DB::table('shipment')->where('id',$shipment_id)->update(['status'=>1,'delivery_date'=>date('Y-m-d')]); 
      if($updated){
        $data['status_no'] = 1; 
      }
      return $data;
    }

    public function makeDelivery($order_no,$shipment_id){
      DB::table('shipment')->where('id',$shipment_id)->update(['status'=>1,'delivery_date'=>date('Y-m-d')]); 

      \Session::flash('success',trans('message.success.save_success'));
      return redirect()->intended('shipment/view-details/'.$order_no.'/'.$shipment_id);

    }

    /**
    * Delete shipment by shipment id
    * @params shipment_id
    */
    public function destroy($shipment_id){
      $shipments = DB::table('shipment')
                             ->where('shipment.id',$shipment_id)
                             ->leftjoin('shipment_details','shipment_details.shipment_id','=','shipment.id')
                             ->select('shipment_details.id','shipment_details.order_no','shipment_details.stock_id','shipment_details.quantity')
                             ->get();
      foreach ($shipments as $key => $shipment) {
        $qty = DB::table('sales_order_details')
             ->where(['order_no'=>$shipment->order_no,'stock_id'=>$shipment->stock_id])
             ->sum('shipment_qty');
        $newQty = ($qty-$shipment->quantity);
        $updated = DB::table('sales_order_details')
                 ->where(['order_no'=>$shipment->order_no,'stock_id'=>$shipment->stock_id])
                 ->update(['shipment_qty'=>$newQty]);
        
        DB::table('shipment_details')->where(['id'=>$shipment->id])->delete();
      }

      DB::table('shipment')->where(['id'=>$shipment_id])->delete();
      
      \Session::flash('success',trans('message.success.delete_success'));
      return redirect()->intended('shipment/list');
    }

    /**
    * Details shipment by shipment id
    * @params shipment_id
    */
    public function shipmentDetails($order_no,$shipment_id){
      $data = array();
      $data['menu'] = 'sales';
      $data['sub_menu'] = 'shipment/list';
      $data['taxInfo'] = $this->shipment->calculateTaxForDetail($shipment_id);
      $data['shipmentItem'] = DB::table('shipment')
                             ->where('shipment.id',$shipment_id)
                             ->leftjoin('shipment_details','shipment_details.shipment_id','=','shipment.id')
                             ->leftjoin('item_code','shipment_details.stock_id','=','item_code.stock_id')
                             ->leftjoin('item_tax_types','item_tax_types.id','=','shipment_details.tax_type_id')
                             ->select('shipment_details.*','item_code.description','item_tax_types.tax_rate')
                             ->orderBy('shipment_details.quantity','DESC')
                             ->get();
      $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$order_no)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','countries.country','cust_branch.shipping_country_id')
                             ->first();
      $data['shipment']   = DB::table('shipment')
                          ->where('id',$shipment_id)
                          ->first();

      // Right side info
      $data['orderInfo']  = DB::table('sales_orders')
                          ->leftjoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                          ->where('order_no',$order_no)
                          ->select('sales_orders.reference','sales_orders.order_no','location.location_name', 'sales_orders.discount_percent')
                          ->first();
      $data['invoiceList'] = DB::table('sales_orders')
                              ->where('order_reference',$data['orderInfo']->reference)
                              ->select('order_no','reference','order_reference','total','paid_amount')
                              ->orderBy('created_at','DESC')
                              ->get();
      $data['invoiceQty'] = DB::table('stock_moves')->where(['order_no'=>$order_no,'trans_type'=>SALESINVOICE])->sum('qty');
      $data['orderQty'] = DB::table('sales_order_details')->where(['order_no'=>$order_no,'trans_type'=>SALESORDER])->sum('quantity');

      $data['paymentsList'] = DB::table('payment_history')
                            ->where(['order_reference'=>$data['orderInfo']->reference])
                            ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                            ->select('payment_history.*','payment_terms.name')
                            ->orderBy('payment_date','DESC')
                            ->get();
      $data['shipmentList'] = DB::table('shipment_details')
                            ->select('shipment_details.shipment_id',DB::raw('sum(quantity) as total'))->where(['order_no'=>$order_no])
                            ->groupBy('shipment_id')
                            ->orderBy('shipment_id','DESC')
                            ->get();
      //d($data['orderInfo'],1);
      $shipmentTotal = $this->shipment->getTotalShipmentByOrderNo($order_no);
     
      $invoicedTotal = $this->shipment->getTotalInvoicedByOrderNo($order_no);
      $shipment = (int)abs($invoicedTotal)-(int)abs($shipmentTotal);
     
      $data['shipmentStatus'] = ($shipment>0) ? 'available' : 'notAvailable';

      
      if($data['shipment']->status == 0){
        $temp_id = 6;
      }else{
        $temp_id = 2;
      }
      $lang = Session::get('dflt_lang');
      $data['emailInfo'] = DB::table('email_temp_details')->where(['temp_id'=>$temp_id,'lang'=>$lang])->select('subject','body')->first();

      //d($data['customerInfo'],1);
      return view('admin.shipment.shipmentDetails', $data);
    }

    public function pdfMake($orderNo,$shipmentId){
        
      $data['taxInfo'] = $this->shipment->calculateTaxForDetail($shipmentId);
      $data['shipmentItem'] = DB::table('shipment')
                             ->where('shipment.id',$shipmentId)
                             ->leftjoin('shipment_details','shipment_details.shipment_id','=','shipment.id')
                             ->leftjoin('item_code','shipment_details.stock_id','=','item_code.stock_id')
                             ->leftjoin('item_tax_types','item_tax_types.id','=','shipment_details.tax_type_id')
                             ->select('shipment_details.*','item_code.description','item_tax_types.tax_rate')
                             ->orderBy('shipment_details.quantity','DESC')
                             ->get();
      $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','countries.country','cust_branch.shipping_country_id', 'sales_orders.discount_percent')
                             ->first();
      $data['shipment']   = DB::table('shipment')->where('id',$shipmentId)->select('id','status','delivery_date')->first();
      $data['order_no']   = $orderNo;
        
      $pdf = PDF::loadView('admin.shipment.shipmentDetailpdf', $data);
      $pdf->setPaper('a4', 'landscape');
        
      return $pdf->download('shipment_'.time().'.pdf',array("Attachment"=>0));
    }

    /**
    * Print of shipment details
    */

    public function shipmentPrint($orderNo,$shipmentId){
        
      $data['taxInfo'] = $this->shipment->calculateTaxForDetail($shipmentId);
      $data['shipmentItem'] = DB::table('shipment')
                             ->where('shipment.id',$shipmentId)
                             ->leftjoin('shipment_details','shipment_details.shipment_id','=','shipment.id')
                             ->leftjoin('item_code','shipment_details.stock_id','=','item_code.stock_id')
                             ->leftjoin('item_tax_types','item_tax_types.id','=','shipment_details.tax_type_id')
                             ->select('shipment_details.*','item_code.description','item_tax_types.tax_rate')
                             ->orderBy('shipment_details.quantity','DESC')
                             ->get();
      $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','countries.country','cust_branch.shipping_country_id', 'sales_orders.discount_percent')
                             ->first();
      //d($data['customerInfo'],1);
      $data['shipment']   = DB::table('shipment')->where('id',$shipmentId)->select('id','status','delivery_date')->first();
      $data['order_no']   = $orderNo;
      //d($data['shipment'],1);
      $pdf = PDF::loadView('admin.shipment.shipmentDetailPrint', $data);
      $pdf->setPaper('a4', 'landscape');
        
      return $pdf->stream('shipment_'.time().'.pdf',array("Attachment"=>0));
    }

    /**
    * Edit shipment by shipment_id
    */
    public function edit($shipmentId){
      $data['menu'] = 'sales';
      $data['sub_menu'] = 'shipment/list';
      $data['shipment_id'] = $shipmentId;           
      $data['taxInfo'] = $this->shipment->calculateTaxForDetail($shipmentId);
      $data['shipmentItem'] = DB::table('shipment')
                             ->where('shipment.id',$shipmentId)
                             ->leftjoin('shipment_details','shipment_details.shipment_id','=','shipment.id')
                             ->leftjoin('item_code','shipment_details.stock_id','=','item_code.stock_id')
                             ->leftjoin('item_tax_types','item_tax_types.id','=','shipment_details.tax_type_id')
                             ->select('shipment_details.*','item_code.description','item_code.id as item_id','item_tax_types.tax_rate')
                             ->orderBy('shipment_details.quantity','DESC')
                             ->get();
      $shipment = DB::table('shipment')
               ->where('id',$shipmentId)
               ->select('order_no')
               ->first();
      
      $data['orderInfo'] = DB::table('sales_orders')
                          ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                          ->where('order_no', '=', $shipment->order_no)
                          ->first();
      //d($data['orderInfo'],1);
      return view('admin.shipment.editShipment', $data);
    }

    public function shipmentQuantityValidation(Request $request){
      $data['status_no'] = 0;
      $data['message'] = 'Not Available';

      $orderNo = $request['order_no'];
      $shipmentId = $request['shipment_id'];
      $stock_id = $request['stock_id'];
      $shifted_qty = $request['shifted_qty'];
      $new_qty = $request['new_qty'];
      
      $invoicedItemQtyInfo = (int)abs(DB::table("stock_moves")->where(['order_no'=>$orderNo,'stock_id'=>$stock_id])->groupBy('order_no')->sum('qty'));
      $shipmentItemQtyInfo = DB::table("sales_order_details")->where(['order_no'=>$orderNo,'stock_id'=>$stock_id])->sum('shipment_qty');
      $availableQty = ($invoicedItemQtyInfo-$shipmentItemQtyInfo+$shifted_qty);
      if($availableQty>$new_qty || $availableQty==$new_qty){
      $data['status_no'] = 1;
      $data['message'] = 'Available';
      }
      return json_encode($data);
    }
    
    /**
    *Update shipment by shipment id
    */
    public function update(Request $request){
      $shipmentId = $request['shipment_id'];
      $orderInfo  = DB::table('shipment')->where('id',$shipmentId)->select('order_no')->first();
      $stockIds = $request['stock_id'];
      $quantityPrevious = $request['previous_qty'];
      $quantityNew = $request['item_quantity'];
      $orderNo = $request['order_no'];
    
      foreach($stockIds as $key=>$stockId){
        $qtyShifted = DB::table('sales_order_details')->where(['stock_id'=>$stockIds[$key],'order_no'=>$orderNo[$key]])->sum('shipment_qty');
        if($quantityPrevious[$key]>$quantityNew[$key]){
          $qtyNew = ($qtyShifted+$quantityNew[$key]-$quantityPrevious[$key]);
          if($quantityNew[$key] == 0){
            DB::table('shipment_details')->where(['shipment_id'=>$shipmentId,'stock_id'=>$stockIds[$key],'order_no'=>$orderNo[$key]])->delete();
          }
        }elseif($quantityPrevious[$key]<$quantityNew[$key]){
          $qtyNew = ($qtyShifted+$quantityNew[$key]-$quantityPrevious[$key]);
        }elseif($quantityPrevious[$key]==$quantityNew[$key]){
          $qtyNew = $qtyShifted; 
        }

        DB::table('sales_order_details')->where(['stock_id'=>$stockIds[$key],'order_no'=>$orderNo[$key]])->update(['shipment_qty'=>$qtyNew]);
        DB::table('shipment_details')->where(['shipment_id'=>$shipmentId,'stock_id'=>$stockIds[$key],'order_no'=>$orderNo[$key]])->update(['quantity'=>$quantityNew[$key]]);
      
      }

        \Session::flash('success',trans('message.success.save_success'));
         return redirect()->intended('shipment/view-details/'.$orderInfo->order_no.'/'.$shipmentId);
    }

    /**
    * Send email to customer for shipment information
    */
    public function sendShipmentInformationByEmail(Request $request){
        $this->email->sendEmail($request['email'],$request['subject'],$request['message']);
        \Session::flash('success',trans('message.email.email_send_success'));
        return redirect()->intended('shipment/view-details/'.$request['order_id'].'/'.$request['shipment_id']);
    }

}
