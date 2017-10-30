<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Sales;
use App\Http\Requests;
use DB;
use PDF;

class SalesController extends Controller
{
    public function __construct(Sales $sales){
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
        $this->sale = $sales;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'sales/direct-invoice';
        $data['salesData'] = $this->sale->getAllSalseOrder($from = NULL, $to = NULL, $item = NULL, $customer = NULL, $location = NULL);
        return view('admin.sale.sales_list', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function salesFiltering()
    {
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'sales/direct-invoice';

        $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : NULL;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : NULL;
        $data['item'] = $item = isset($_GET['product']) ? $_GET['product'] : NULL;

        $data['customerList'] = DB::table('debtors_master')->select('debtor_no','name')->where(['inactive'=>0])->get();
        $data['locationList'] = DB::table('location')->select('loc_code','location_name')->get();
        $data['productList'] = DB::table('item_code')->where(['inactive'=>0,'deleted_status'=>0])->select('stock_id','description')->get();
        
        $fromDate = DB::table('sales_orders')->select('ord_date')->where('trans_type',SALESINVOICE)->orderBy('ord_date','asc')->first();
        
        if(isset($_GET['from'])){
            $data['from'] = $from = $_GET['from'];
        }else{
           $data['from'] = $from = formatDate(date("d-m-Y", strtotime($fromDate->ord_date))); 
        }
        
        if(isset($_GET['to'])){
            $data['to'] = $to = $_GET['to'];
        }else{
            $data['to'] = $to = formatDate(date('d-m-Y'));
        }


        $data['salesData'] = $this->sale->getAllSalseOrder($from, $to, $item, $customer, $location);
        return view('admin.sale.sales_list_filter', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'sales/direct-invoice';
        $data['customerData'] = DB::table('debtors_master')->where(['inactive'=>0])->get();
        $data['locData'] = DB::table('location')->get();
        $data['payments'] = DB::table('payment_terms')->get();
        //d($data['payments'],1);
        $data['paymentTerms'] = DB::table('invoice_payment_terms')->get();
        $invoice_count = DB::table('sales_orders')->where('trans_type',SALESINVOICE)->count();
        
        $data['salesType'] = DB::table('sales_types')->select('sales_type','id')->get();        
        
        if($invoice_count>0){
        $invoiceReference = DB::table('sales_orders')->where('trans_type',SALESINVOICE)->select('reference')->orderBy('order_no','DESC')->first();

        $ref = explode("-",$invoiceReference->reference);
        $data['invoice_count'] = (int) $ref[1];
        }else{
            $data['invoice_count'] = 0 ;
        }
        
        $taxTypeList = DB::table('item_tax_types')->get();
        $taxOptions = '';
        $selectStart = "<select class='form-control taxList' name='tax_id[]'>";
        $selectEnd = "</select>";
        
        foreach ($taxTypeList as $key => $value) {
            $taxOptions .= "<option value='".$value->id."' taxrate='".$value->tax_rate."'>".$value->name.'('.$value->tax_rate.')'."</option>";          
        }
        $data['tax_type'] = $selectStart.$taxOptions.$selectEnd;

        return view('admin.sale.sale_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     **/
    public function store(Request $request)
    {
        $userId = \Auth::user()->id;
        
        $this->validate($request, [
            'reference'=>'required|unique:sales_orders',
            'from_stk_loc' => 'required',
            'ord_date' => 'required',
            'debtor_no' => 'required',
            'branch_id' => 'required',
            'payment_id' => 'required',
            'item_quantity' => 'required',
        ]);
        
        $itemQuantity = $request->item_quantity;        
        $itemIds = $request->item_id;
        $itemDiscount = $request->discount;
        $taxIds = $request->tax_id;
        $unitPrice = $request->unit_price;
        $stock_id = $request->stock_id;
        $description = $request->description;

        foreach ($itemQuantity as $key => $itemQty) {
            $product[$itemIds[$key]] = $itemQty;
        }
       
        // create salesOrder start
        $orderReferenceNo = DB::table('sales_orders')->where('trans_type',SALESORDER)->count();

        if($orderReferenceNo>0){
        $orderReference = DB::table('sales_orders')->where('trans_type',SALESORDER)->select('reference')->orderBy('order_no','DESC')->first();
        $ref = explode("-",$orderReference->reference);
        $orderCount = (int) $ref[1];
        }else{
            $orderCount = 0 ;
        }

        $salesOrder['debtor_no'] = $request->debtor_no;
        $salesOrder['branch_id'] = $request->branch_id;
        $salesOrder['payment_id']= $request->payment_id;
        $salesOrder['person_id']= $userId;
        $salesOrder['reference'] ='SO-'. sprintf("%04d", $orderCount+1);
        $salesOrder['comments'] = $request->comments;
        $salesOrder['trans_type'] = SALESORDER;
        $salesOrder['ord_date'] = DbDateFormat($request->ord_date);
        $salesOrder['from_stk_loc'] = $request->from_stk_loc;
        $salesOrder['total'] = $request->total;
        $salesOrder['created_at'] = date('Y-m-d H:i:s');
        $salesOrderId = DB::table('sales_orders')->insertGetId($salesOrder);
        // create salesOrder end

        // Create salesOrder Invoice start
        $salesOrderInvoice['order_reference_id'] = $salesOrderId;
        $salesOrderInvoice['order_reference'] = $salesOrder['reference'];
        $salesOrderInvoice['trans_type'] = SALESINVOICE;
        $salesOrderInvoice['reference'] = $request->reference;
        $salesOrderInvoice['debtor_no'] = $request->debtor_no;
        $salesOrderInvoice['branch_id'] = $request->branch_id;
        $salesOrderInvoice['payment_id']= $request->payment_id;
        $salesOrderInvoice['person_id']= $userId;
        $salesOrderInvoice['comments'] = $request->comments;
        $salesOrderInvoice['ord_date'] = DbDateFormat($request->ord_date);
        $salesOrderInvoice['from_stk_loc'] = $request->from_stk_loc;
        $salesOrderInvoice['total'] = $request->total;
        $salesOrderInvoice['payment_term'] = $request->payment_term;
        $salesOrderInvoice['created_at'] = date('Y-m-d H:i:s');

        $orderInvoiceId = DB::table('sales_orders')->insertGetId($salesOrderInvoice);
        // Create salesOrder Invoice end

        for ($i=0; $i < count($itemIds); $i++) {
            foreach ($product as $key => $item) {
                
                if($itemIds[$i] == $key){
                   
                    // create salesOrderDetail Start
                    $salesOrderDetail[$i]['order_no'] = $salesOrderId;
                    $salesOrderDetail[$i]['stock_id'] = $stock_id[$i];
                    $salesOrderDetail[$i]['description'] = $description[$i];
                    $salesOrderDetail[$i]['qty_sent'] = $item;
                    $salesOrderDetail[$i]['quantity'] = $item;
                    $salesOrderDetail[$i]['trans_type'] = SALESORDER;
                    $salesOrderDetail[$i]['discount_percent'] = $itemDiscount[$i];
                    $salesOrderDetail[$i]['tax_type_id'] = $taxIds[$i];
                    $salesOrderDetail[$i]['unit_price'] = $unitPrice[$i];
                    
                    // Create salesOrderDetailInvoice Start
                    $salesOrderDetailInvoice[$i]['order_no'] = $orderInvoiceId;
                    $salesOrderDetailInvoice[$i]['stock_id'] = $stock_id[$i];
                    $salesOrderDetailInvoice[$i]['description'] = $description[$i];
                    $salesOrderDetailInvoice[$i]['qty_sent'] = $item;
                    $salesOrderDetailInvoice[$i]['quantity'] = $item;
                    $salesOrderDetailInvoice[$i]['trans_type'] = SALESINVOICE;
                    $salesOrderDetailInvoice[$i]['discount_percent'] = $itemDiscount[$i];
                    $salesOrderDetailInvoice[$i]['tax_type_id'] = $taxIds[$i];
                    $salesOrderDetailInvoice[$i]['unit_price'] = $unitPrice[$i];
                    // Create salesOrderDetailInvoice End

                    // create stockMove 
                    $stockMove[$i]['stock_id'] = $stock_id[$i];
                    $stockMove[$i]['loc_code'] = $request->from_stk_loc;
                    $stockMove[$i]['tran_date'] = DbDateFormat($request->ord_date);
                    $stockMove[$i]['person_id'] = $userId;
                    $stockMove[$i]['reference'] = 'store_out_'.$orderInvoiceId;
                    $stockMove[$i]['transaction_reference_id'] =$orderInvoiceId;
                    $stockMove[$i]['qty'] = '-'.$item;
                    $stockMove[$i]['price'] = $unitPrice[$i];
                    $stockMove[$i]['trans_type'] = SALESINVOICE;
                    $stockMove[$i]['order_no'] = $salesOrderId;
                    $stockMove[$i]['order_reference'] = $salesOrder['reference'];
                }
            }
        }

        for ($i=0; $i < count($salesOrderDetailInvoice); $i++) { 
            
            DB::table('sales_order_details')->insertGetId($salesOrderDetail[$i]);
            DB::table('sales_order_details')->insertGetId($salesOrderDetailInvoice[$i]);
            DB::table('stock_moves')->insertGetId($stockMove[$i]);
        }

        if(!empty($orderInvoiceId)){
            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('invoice/view-detail-invoice/'.$salesOrderId.'/'.$orderInvoiceId);
        }

    }

    /**
     * Show the form for editing the specified resource.
     **/
    public function edit($orderNo)
    {
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'sales/direct-invoice';
        $data['taxType'] = $this->sale->calculateTaxRow($orderNo);
        $data['customerData'] = DB::table('debtors_master')->get();
        $data['locData'] = DB::table('location')->get();
        $data['invoiceData'] = $this->sale->getSalseInvoiceByID($orderNo);
        $data['saleData'] = DB::table('sales_orders')->where('order_no', '=', $orderNo)->first();
        $data['branchs'] = DB::table('cust_branch')->select('debtor_no','branch_code','br_name')->where('debtor_no',$data['saleData']->debtor_no)->orderBy('br_name','ASC')->get();
        $data['payments'] = DB::table('payment_terms')->get();
        $data['paymentTerms'] = DB::table('invoice_payment_terms')->get();
        $data['inoviceInfo'] = DB::table('sales_orders')->where('order_no', '=', $orderNo)->first();

        return view('admin.sale.sale_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     **/
    public function update(Request $request)
    {

        $userId = \Auth::user()->id;
        $order_no = $request->order_no;
        $order_ref_no = $request->order_reference_id;
        $this->validate($request, [
            'reference'=>'required|unique:sales_orders,reference,'.$order_no.',order_no',
            'from_stk_loc' => 'required',
            'ord_date' => 'required',
            'debtor_no' => 'required',
            'branch_id' => 'required',
            'payment_id' => 'required'
        ]);
        
        $itemQty = $request->item_quantity;
        $itemIds = $request->item_id; 
        $unitPrice = $request->unit_price;
        $taxIds = $request->tax_id;                
        $itemDiscount = $request->discount;
        $itemPrice = $request->item_price; 
        $description = $request->description;
        $stock_id = $request->stock_id; 

        // update sales_order table
        $salesOrder['ord_date'] = DbDateFormat($request->ord_date);
        $salesOrder['debtor_no'] = $request->debtor_no;
        $salesOrder['trans_type'] = SALESINVOICE;
        $salesOrder['branch_id'] = $request->branch_id;
        $salesOrder['payment_id'] = $request->payment_id;
       // $salesOrder['reference'] = $request->reference;
        $salesOrder['from_stk_loc'] = $request->from_stk_loc;
        $salesOrder['comments'] = $request->comments;
        $salesOrder['total'] = $request->total;
        $salesOrder['delivery_price'] = $request->delivery_price;
        $salesOrderInvoice['discount_type']              = $request->discount_type;
        $salesOrderInvoice['discount_percent']              = $request->perOrderDiscount;
        $salesOrder['payment_term'] = $request->payment_term;
        $salesOrder['updated_at'] = date('Y-m-d H:i:s');
       // d($salesOrder);

        DB::table('sales_orders')->where('order_no', $order_no)->update($salesOrder);
       
        if(count($itemQty)>0) {
            foreach ($itemQty as $key => $value) {
                $product[$itemIds[$key]] = $value;
            }

            for ($i=0; $i < count($itemIds); $i++) {
                foreach ($product as $key => $value) {
                    if($itemIds[$i] == $key){
                        // update sales_order_details table
                        $salesOrderDetail[$i]['stock_id'] = $stock_id[$i];
                        $salesOrderDetail[$i]['description'] = $description[$i];
                        $salesOrderDetail[$i]['unit_price'] = $unitPrice[$i];
                        $salesOrderDetail[$i]['qty_sent'] = $value;
                        $salesOrderDetail[$i]['trans_type'] = SALESINVOICE;
                        $salesOrderDetail[$i]['quantity'] = $value;
                        $salesOrderDetail[$i]['discount_percent'] = $itemDiscount[$i];
                        
                        // Update stock_move table
                        $stockMove[$i]['stock_id'] = $stock_id[$i];
                        $stockMove[$i]['trans_type'] = SALESINVOICE;
                        $stockMove[$i]['loc_code'] = $request->from_stk_loc;
                        $stockMove[$i]['tran_date'] = DbDateFormat($request->ord_date);
                        $stockMove[$i]['person_id'] = $userId;
                        $stockMove[$i]['reference'] = 'store_out_'.$order_no;
                        $stockMove[$i]['transaction_reference_id'] =$order_no;
                        $stockMove[$i]['qty'] = '-'.$value;
                        $stockMove[$i]['note'] = $request->comments;

                    }
                }
            }

            for ($i=0; $i < count($salesOrderDetail); $i++) { 
                DB::table('sales_order_details')->where(['stock_id'=>$salesOrderDetail[$i]['stock_id'],'order_no'=>$order_no])->update($salesOrderDetail[$i]);
                DB::table('stock_moves')->where(['stock_id'=>$stockMove[$i]['stock_id'],'reference'=>'store_out_'.$order_no])->update($stockMove[$i]);
            }

        }

        \Session::flash('success',trans('message.success.save_success'));
         return redirect()->intended('invoice/view-detail-invoice/'.$order_ref_no.'/'.$order_no);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(isset($id)) {
            $record = \DB::table('sales_orders')->where('order_no', $id)->first();
            if($record) {
                
                DB::table('sales_orders')->where('order_no', '=', $record->order_no)->delete();
                DB::table('sales_order_details')->where('order_no', '=', $record->order_no)->delete();
                DB::table('stock_moves')->where('reference', '=', 'store_out_'.$record->order_no)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('sales/list');
            }
        }
    }

    public function search(Request $request)
    {
            
            $data = array();
            $data['status_no'] = 0;
            $data['message']   ='No Item Found!';
            $data['items'] = array();
            
            $item = DB::table('stock_master')
            ->where('stock_master.description','LIKE','%'.$request->search.'%')
            ->where(['stock_master.inactive'=>0,'stock_master.deleted_status'=>0])
            ->orWhere('stock_master.stock_id','LIKE','%'.$request->search.'%')
            ->leftJoin('item_tax_types','item_tax_types.id','=','stock_master.tax_type_id')
            ->leftJoin('item_code','stock_master.stock_id','=','item_code.stock_id')
            ->select('stock_master.*','item_tax_types.tax_rate','item_tax_types.id as tax_id','item_code.id')
            ->get();

            if(!empty($item)){
                $data['status_no'] = 1;
                $data['message']   ='Item Found';

                $i = 0;
                foreach ($item as $key => $value) {
                    $itemPriceValue = DB::table('sale_prices')->where(['stock_id'=>$value->stock_id,'sales_type_id'=>$request['salesTypeId']])->select('price')->first();
                    if(!isset($itemPriceValue)){
                        $itemSalesPriceValue = 0;
                    }else{
                        $itemSalesPriceValue = $itemPriceValue->price;
                    }
                    $return_arr[$i]['id'] = $value->id;
                    $return_arr[$i]['stock_id'] = $value->stock_id;
                    $return_arr[$i]['description'] = $value->description;
                    $return_arr[$i]['units'] = $value->units;
                    $return_arr[$i]['price'] = $itemSalesPriceValue;
                    $return_arr[$i]['tax_rate'] = $value->tax_rate;
                    $return_arr[$i]['tax_id'] = $value->tax_id;
                    $i++;
                }
               // echo json_encode($return_arr);
                 $data['items'] = $return_arr;
            }
            echo json_encode($data);
            exit;  

    }

    public function checkItemQty(Request $request)
    {
       $data = array();
        $location = $request['loc_code'];
        $stock_id = $request['stock_id'];
        $itemQty = $this->sale->stockValidate($location,$stock_id);
        
        if($itemQty <= 0){
            $data['status_no'] = 1; 
        }

        return json_encode($data);        

    }
    /**
    * Return quantity validation result
    */
    public function quantityValidation(Request $request){
        $data = array();
        $location = $request['location_id'];
        $setItem = $request['qty'];

        $item_code = DB::table('item_code')->where("id",$request['id'])->select('stock_id')->first();
        
        $availableItem = $this->sale->stockValidate($location,$item_code->stock_id);

        $data['availableItem'] = $availableItem;
        $data['message'] = trans('message.table.tax').$availableItem;

        return json_encode($data);
    }
    
    /**
    * Check reference no if exists
    */
    public function referenceValidation(Request $request){
        
        $data = array();
        $ref = $request['ref'];
        $result = DB::table('sales_orders')->where("reference",$ref)->first();

        if(count($result)>0){
            $data['status_no'] = 1; 
        }else{
            $data['status_no'] = 0;
        }

        return json_encode($data);       
    }

    /**
    * Return customer Branches by customer id
    */
    public function customerBranches(Request $request){
        $debtor_no = $request['debtor_no'];
        $data['status_no'] = 0;
        $branchs = '';
        $result = DB::table('cust_branch')->select('debtor_no','branch_code','br_name')->where('debtor_no',$debtor_no)->orderBy('br_name','ASC')->get();
        if(!empty($result)){
            $data['status_no'] = 1;
            foreach ($result as $key => $value) {
            $branchs .= "<option value='".$value->branch_code."'>".$value->br_name."</option>";  
        }
        $data['branchs'] = $branchs; 
       }
        return json_encode($data);
    }

    public function quantityValidationWithLocaltion(Request $request){
        $location = $request['location'];
        $items    = $request['itemInfo'];
        $data['status_no'] = 0;
        $data['item']      = trans('message.invoice.item_insufficient_message');
        //d($items,1);
        foreach($items as $result){
        $qty = DB::table('stock_moves')
                     ->select(DB::raw('sum(qty) as total'))
                     ->where(['stock_id'=>$result['stockid'], 'loc_code'=>$location])
                     ->groupBy('loc_code')
                     ->first();
        if(empty($qty)){
            return json_encode($data);
        }else if($qty<$result['qty']){
            return json_encode($data);
        }else{
            $datas['status_no'] = 1;
            return json_encode($datas);
          }
       }
    }

    public function quantityValidationEditInvoice(Request $request){
        
        $location = $request['location_id'];
        $item_id = $request['item_id'];
        $stock_id = $request['stock_id'];
        $set_qty = $request['qty'];
        $invoice_order_no = $request['invoice_no'];
        $order_reference = $request['order_reference'];
        $order = DB::table('sales_orders')->where('reference',$request['order_reference'])->select('order_no')->first();
        $orderItemQty = DB::table('sales_order_details')
                        ->where(['order_no'=>$order->order_no,'stock_id'=>$stock_id])
                        ->select('quantity')
                        ->first();
       
        $salesItemQty = DB::table('stock_moves')
                        ->where(['order_reference'=>$order_reference,'stock_id'=>$stock_id,'loc_code'=>$location])
                        ->where('reference','!=','store_out_'.$invoice_order_no)
                        ->sum('qty');

        $itemAvailable = $orderItemQty->quantity + ($salesItemQty);

       if($set_qty>$itemAvailable){
            $data['status_no'] = 0;
            $data['qty']       ="qty Insufficient";
       }else{
        $data['status_no'] = 1;
        $data['qty']       ="qty available";
       }
       return json_encode($data);
    }
}
