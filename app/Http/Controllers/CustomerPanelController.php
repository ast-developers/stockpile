<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Model\Orders;
use App\Model\Sales;
use App\Model\Shipment;
use DB;
use PDF;

class CustomerPanelController extends Controller
{
	public function __construct(Orders $orders,Sales $sales,Shipment $shipment) {
       $this->middleware('customer');
       $this->order = $orders;
       $this->sale = $sales;
       $this->shipment = $shipment;
    }

    public function index()
    {
       // $userStatus = Auth::guard('customer')->user()->inactive;
        $data['menu'] = 'home';
        $uid = Auth::guard('customer')->user()->debtor_no;

        $data['totalOrder'] = DB::table('sales_orders')->where(['trans_type'=>SALESORDER,'debtor_no'=>$uid])->count();
        $data['totalInvoice'] = DB::table('sales_orders')->where(['trans_type'=>SALESINVOICE,'debtor_no'=>$uid])->count();
        $data['totalShipment'] = DB::table('shipment')
                ->leftJoin('shipment_details', 'shipment.id', '=', 'shipment_details.shipment_id')
                ->leftJoin('sales_orders','sales_orders.order_no','=','shipment_details.order_no')
                ->leftJoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                ->where('debtors_master.debtor_no', $uid)
                ->select('shipment_details.shipment_id','sales_orders.reference','sales_orders.order_no as order_id','debtors_master.name','shipment.packed_date','shipment.status', DB::raw('sum(shipment_details.quantity) as total_shipment'))
                ->groupBy('shipment_details.shipment_id')
                ->orderBy('shipment.packed_date','DESC')
                ->count();
        $data['totalBranch'] = DB::table('cust_branch')->where(['debtor_no'=>$uid])->count();
        //d($data['totalBranch'],1);
        $data['uid'] = $uid;
        return view('admin.customer.customer_panel',$data);
    }

    public function profile()
    {
        $id = Auth::guard('customer')->user()->debtor_no;
        $data['userData'] = DB::table('debtors_master')->where('debtor_no', '=', $id)->first();
        
        return view('admin.customerPanel.editProfile', $data);
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'min:5|confirmed',
            'password_confirmation' => 'min:5'
        ]);

        $id = Auth::guard('customer')->user()->debtor_no;

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if($request->password) {
            $data['password'] = \Hash::make($request->password);
        }
        
        
        DB::table('debtors_master')->where('debtor_no', $id)->update($data);
        \Session::flash('success',trans('message.success.update_success'));
        return redirect()->intended("customer/profile");
    }

    public function salesOrder($id)
    {
        $data['menu'] = 'order';
        
        $data['salesOrderData'] = $this->order->getAllSalseOrderByCustomer($id);
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();

        return view('admin.customerPanel.customer_sales_order', $data);
    }

    public function invoice($id)
    {
        $data['menu'] = 'invoice';
        $data['salesOrderData'] = $this->order->getAllSalseInvoiceByCustomer($id);
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();

        return view('admin.customerPanel.customer_invoice', $data);
    }

    public function payment($id)
    {
        $data['menu'] = 'payment';

        
        $data['paymentList'] = DB::table('payment_history')
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','payment_history.customer_id')
                             ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                             ->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
                             ->where('sales_orders.debtor_no',$id)
                             ->select('payment_history.*','debtors_master.name','payment_terms.name as pay_type','sales_orders.order_no as invoice_id','sales_orders.order_reference_id as order_id')
                             ->orderBy('payment_date','DESC')
                             ->get();
        //d($data['paymentList'],1);
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();

        return view('admin.customerPanel.customer_payment', $data);
    }

    public function shipment($id)
    {
        $data['menu'] = 'shipment';
        
        $data['shipmentList'] = DB::table('shipment')
                ->leftJoin('shipment_details', 'shipment.id', '=', 'shipment_details.shipment_id')
                ->leftJoin('sales_orders','sales_orders.order_no','=','shipment_details.order_no')
                ->leftJoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                ->where('debtors_master.debtor_no', $id)
                ->select('shipment_details.shipment_id','sales_orders.reference','sales_orders.order_no as order_id','debtors_master.name','shipment.packed_date','shipment.status', DB::raw('sum(shipment_details.quantity) as total_shipment'))
                ->groupBy('shipment_details.shipment_id')
                //->orderBy('shipment_details.delivery_date','DESC')
                ->orderBy('shipment.packed_date','DESC')
                ->get();
        //d($data,1);

        return view('admin.customerPanel.customer_shipment', $data);
    }

    public function branch($id)
    {
        $data['menu'] = 'branch';
        $data['cusBranchData'] = DB::table('cust_branch')->where('debtor_no', $id)->get();

        return view('admin.customerPanel.customer_branch', $data);
        //d($data['branchList'],1);
    }

    public function branchEdit($id)
    {
        $data['menu'] = 'branch';
        $data['countries'] = DB::table('countries')->get();
        $data['branchData'] = DB::table('cust_branch')->where('branch_code', $id)->first();
        //d($data['branchData'],1);

        return view('admin.customerPanel.customer_branch_edit', $data);
    }

    public function branchUpdate($id)
    {
        //dd($_POST);
        $data['br_name'] = $_POST['br_name'];
        $data['br_contact'] = $_POST['br_contact'];
        //$data['br_address'] = $_POST['br_address'];
        $data['billing_street'] = $_POST['bill_street'];
        $data['billing_city'] = $_POST['bill_city'];
        $data['billing_state'] = $_POST['bill_state'];
        $data['billing_zip_code'] = $_POST['bill_zipCode'];
        $data['billing_country_id'] = $_POST['bill_country_id'];
        $data['shipping_street'] = $_POST['ship_street'];
        $data['shipping_city'] = $_POST['ship_city'];
        $data['shipping_state'] = $_POST['ship_state'];
        $data['shipping_zip_code'] = $_POST['ship_zipCode'];
        $data['shipping_country_id'] = $_POST['ship_country_id'];
        //d($data,1);
        DB::table('cust_branch')->where('branch_code', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
        return redirect()->intended("customer-panel/branch/edit/$id");
    }


    /**
    * Preview of order details
    * @params order_no
    **/

    public function viewOrderDetails($orderNo){
        $data['menu'] = 'order';
        $data['taxType'] = $this->sale->calculateTaxRow($orderNo);
        $data['customerData'] = DB::table('debtors_master')->get();

        $data['saleData'] = DB::table('sales_orders')
                            ->where('order_no', '=', $orderNo)
                            ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                            ->select("sales_orders.*","location.location_name")
                            ->first();
        $data['invoiceData'] = $this->order->getSalseOrderByID($orderNo,$data['saleData']->from_stk_loc);

        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','cust_branch.shipping_country_id','countries.country')                            
                             ->first();   
        $data['orderInfo']    = DB::table('sales_orders')->where('order_no',$orderNo)->select('reference','order_no','ord_date')->first();                     
        $data['paymentsList'] = DB::table('payment_history')
                                ->where(['order_reference'=>$data['orderInfo']->reference])
                                ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                                ->select('payment_history.*','payment_terms.name')
                                ->orderBy('payment_date','DESC')
                                ->get();  
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();                        
      
        return view('admin.customerPanel.viewOrderDetails', $data);

    }

        /**
    * Preview of order details
    * @params order_no
    **/

    public function orderPdf($orderNo){
        $data['taxInfo'] = $this->sale->calculateTaxRow($orderNo);
        $data['saleData'] = DB::table('sales_orders')
                            ->where('order_no', '=', $orderNo)
                            ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                            ->select("sales_orders.*","location.location_name")
                            ->first();
        $data['invoiceData'] = $this->order->getSalseOrderByID($orderNo,$data['saleData']->from_stk_loc);
        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','cust_branch.shipping_country_id','countries.country')                            
                             ->first(); 
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();
        
        $pdf = PDF::loadView('admin.customerPanel.orderPdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('order_'.time().'.pdf',array("Attachment"=>0));
    }


        /**
    * Preview of order details
    * @params order_no
    **/

    public function orderPrint($orderNo){
        $data['taxInfo'] = $this->sale->calculateTaxRow($orderNo);
        $data['saleData'] = DB::table('sales_orders')
                            ->where('order_no', '=', $orderNo)
                            ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                            ->select("sales_orders.*","location.location_name")
                            ->first();
        $data['invoiceData'] = $this->order->getSalseOrderByID($orderNo,$data['saleData']->from_stk_loc);
        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','cust_branch.shipping_country_id','countries.country')                            
                             ->first(); 
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();
        
        $pdf = PDF::loadView('admin.customerPanel.orderPrint', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('order_'.time().'.pdf',array("Attachment"=>0));
    }



    /**
    * Preview of Invoice details
    * @params order_no, invoice_no
    **/

    public function viewInvoiceDetails($orderNo,$invoiceNo){
        $data['menu'] = 'invoice';

        $data['taxType'] = $this->sale->calculateTaxRow($invoiceNo);

        $data['saleDataOrder'] = DB::table('sales_orders')
                            ->where('order_no', '=', $orderNo)
                            ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                            ->select("sales_orders.*","location.location_name")
                            ->first();

        $data['saleDataInvoice'] = DB::table('sales_orders')
                    ->where('order_no', '=', $invoiceNo)
                    ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                    ->leftJoin('invoice_payment_terms','invoice_payment_terms.id','=','sales_orders.payment_term')
                    ->select("sales_orders.*","location.location_name",'invoice_payment_terms.days_before_due')
                    ->first();                    
        $data['invoiceData'] = $this->order->getSalseOrderByID($invoiceNo,$data['saleDataInvoice']->from_stk_loc);

        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','cust_branch.shipping_country_id','countries.country')                            
                             ->first();

        $data['orderInfo']  = DB::table('sales_orders')->where('order_no',$orderNo)->select('reference','order_no')->first();
        $data['paymentsList'] = DB::table('payment_history')
                                ->where(['order_reference'=>$data['orderInfo']->reference])
                                ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                                ->select('payment_history.*','payment_terms.name')
                                ->orderBy('payment_date','DESC')
                                ->get();

        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['saleDataInvoice']->days_before_due."days")));
        //d( $data['saleDataInvoice'] ,1);
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first(); 
        return view('admin.customerPanel.viewInvoiceDetails', $data);
    }
   /**
    * Generate pdf for invoice
    */
    public function invoicePdf($orderNo,$invoiceNo){

        $data['taxInfo'] = $this->sale->calculateTaxRow($invoiceNo);
        $data['saleDataInvoice'] = DB::table('sales_orders')
                    ->where('order_no', '=', $invoiceNo)
                    ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                    ->leftJoin('invoice_payment_terms','invoice_payment_terms.id','=','sales_orders.payment_term')
                    ->select("sales_orders.*","location.location_name",'invoice_payment_terms.days_before_due')
                    ->first();                    
        $data['invoiceData'] = $this->order->getSalseOrderByID($invoiceNo,$data['saleDataInvoice']->from_stk_loc);

        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','cust_branch.shipping_country_id','countries.country')                            
                             ->first();
        $data['orderInfo']  = DB::table('sales_orders')->where('order_no',$orderNo)->select('reference','order_no')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['saleDataInvoice']->days_before_due."days")));
        
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();
        
        $pdf = PDF::loadView('admin.customerPanel.invoicePdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('invoice_'.time().'.pdf',array("Attachment"=>0));        
    }


   /**
    * Generate pdf for invoice
    */
    public function invoicePrint($orderNo,$invoiceNo){

        $data['taxInfo'] = $this->sale->calculateTaxRow($invoiceNo);
        $data['saleDataInvoice'] = DB::table('sales_orders')
                    ->where('order_no', '=', $invoiceNo)
                    ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                    ->leftJoin('invoice_payment_terms','invoice_payment_terms.id','=','sales_orders.payment_term')
                    ->select("sales_orders.*","location.location_name",'invoice_payment_terms.days_before_due')
                    ->first();                    
        $data['invoiceData'] = $this->order->getSalseOrderByID($invoiceNo,$data['saleDataInvoice']->from_stk_loc);

        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','cust_branch.shipping_country_id','countries.country')                            
                             ->first();
        $data['orderInfo']  = DB::table('sales_orders')->where('order_no',$orderNo)->select('reference','order_no')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['saleDataInvoice']->days_before_due."days")));
        
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();
        
        $pdf = PDF::loadView('admin.customerPanel.invoicePrint', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('invoice_'.time().'.pdf',array("Attachment"=>0));        
    }

    /**
    * Display receipt of payment
    */

    public function viewReceipt($id){
        $data['menu'] = 'payment';
                
        $data['paymentInfo'] = DB::table('payment_history')
                     ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                     ->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
                     ->leftjoin('debtors_master','debtors_master.debtor_no','=','payment_history.customer_id')
                     ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                     ->leftjoin('countries','countries.id','=','cust_branch.billing_country_id')
                     ->where('payment_history.id',$id)
                     ->select('payment_history.*','payment_terms.name as payment_method','cust_branch.br_name as branch_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','sales_orders.ord_date as invoice_date','sales_orders.total as invoice_amount','sales_orders.order_reference_id','countries.country','debtors_master.email','debtors_master.phone','debtors_master.name')      
                     ->first();
        $data['settings'] = DB::table('preference')->get();
        $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();
        
        return view('admin.customerPanel.viewReceipt', $data); 
    }

    /**
    * Details shipment by shipment id
    * @params shipment_id
    */
    public function shipmentDetails($order_no,$shipment_id){
      $data = array();
      $data['menu'] = 'shipment';
     
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
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','cust_branch.billing_country_id','cust_branch.shipping_street','cust_branch.shipping_city','cust_branch.shipping_state','cust_branch.shipping_zip_code','cust_branch.shipping_country_id','countries.country')                            
                             ->first();

      $data['shipment']   = DB::table('shipment')
                          ->where('id',$shipment_id)
                          ->first();


      $shipmentTotal = $this->shipment->getTotalShipmentByOrderNo($order_no);
      $invoicedTotal = $this->shipment->getTotalInvoicedByOrderNo($order_no);
      $shipment = (int)abs($invoicedTotal)-$shipmentTotal;
      
      $data['shipmentStatus'] = ($shipment>0) ? 'available' : 'notAvailable';
      
      $data['settings'] = DB::table('preference')->get();
      $data['currency'] = DB::table('currency')->where('id',$data['settings'][17]->value)->first();
        
      return view('admin.customerPanel.shipmentDetails', $data);
    }    


}