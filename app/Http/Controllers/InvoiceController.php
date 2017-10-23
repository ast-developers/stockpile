<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use App\Model\Orders;
use App\Http\Requests;
use App\Model\Sales;
use App\Model\Shipment;
use DB;
use PDF;
use Session;

class InvoiceController extends Controller
{
    public function __construct(Orders $orders,Sales $sales,Shipment $shipment,EmailController $email){
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
        $this->order = $orders;
        $this->sale = $sales;
        $this->shipment = $shipment;
        $this->email = $email;
    }

    /**
    * Preview of Invoice details
    * @params order_no, invoice_no
    **/

    public function viewInvoiceDetails($orderNo,$invoiceNo){
        $data['menu'] = 'sales';
        $data['sub_menu'] = 'sales/direct-invoice';

        $data['taxType'] = $this->sale->calculateTaxRow($invoiceNo);
        $data['locData'] = DB::table('location')->get();
        
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
       
       //d($data['saleDataOrder']);
       //d($data['saleDataInvoice'],1);

        $data['invoice_count'] = DB::table('sales_orders')->where('trans_type',SALESINVOICE)->count();

        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','countries.country','cust_branch.billing_country_id')                            
                             ->first();
        //d($data['customerInfo'],1);

        $data['customer_branch'] = DB::table('cust_branch')->where('branch_code',$data['saleDataOrder']->branch_id)->first();
        $data['customer_payment'] = DB::table('payment_terms')->where('id',$data['saleDataOrder']->payment_id)->first();
      
        $data['invoiceList'] = DB::table('sales_orders')
                            ->where('order_reference',$data['saleDataOrder']->reference)
                            ->select('order_no','reference','order_reference','total','paid_amount')
                            ->orderBy('created_at','DESC')
                            ->get();
        $data['orderInfo']  = DB::table('sales_orders')->where('order_no',$orderNo)->select('reference','order_no')->first();
        $data['invoiceQty'] = DB::table('stock_moves')->where(['order_no'=>$orderNo,'trans_type'=>SALESINVOICE])->sum('qty');
        $data['orderQty']   = DB::table('sales_order_details')->where(['order_no'=>$orderNo,'trans_type'=>SALESORDER])->sum('quantity');
        $data['payments']   = DB::table('payment_terms')->get();
        $data['paymentsList'] = DB::table('payment_history')
                                ->where(['order_reference'=>$data['orderInfo']->reference])
                                ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                                ->select('payment_history.*','payment_terms.name')
                                ->orderBy('payment_date','DESC')
                                ->get();
        $data['shipmentList'] = DB::table('shipment_details')
                            ->select('shipment_details.shipment_id',DB::raw('sum(quantity) as total'))->where(['order_no'=>$orderNo])
                            ->groupBy('shipment_id')
                            ->orderBy('shipment_id','DESC')
                            ->get();
        $shipmentTotal = $this->shipment->getTotalShipmentByOrderNo($orderNo);
        $invoicedTotal = $this->shipment->getTotalInvoicedByOrderNo($orderNo);
        $shipment = (int)abs($invoicedTotal)-$shipmentTotal;
        //d($shipment);
        $data['shipmentStatus'] = ($shipment>0) ? 'available' : 'notAvailable';
        //d($invoicedTotal);
        //d($shipmentTotal,1);
        $data['invoice_no'] = $invoiceNo;
        $lang = Session::get('dflt_lang');
        $data['emailInfo'] = DB::table('email_temp_details')->where(['temp_id'=>4,'lang'=>$lang])->select('subject','body')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['saleDataInvoice']->days_before_due."days")));
        //d( $data['saleDataInvoice'] ,1);
        return view('admin.invoice.viewInvoiceDetails', $data);
    }
    
    /**
    * Send email to customer for Invoice information
    */
    public function sendInvoiceInformationByEmail(Request $request){
        $this->email->sendEmail($request['email'],$request['subject'],$request['message']);
        \Session::flash('success',trans('message.email.email_send_success'));
        return redirect()->intended('invoice/view-detail-invoice/'.$request['order_id'].'/'.$request['invoice_id']);
    }
    
    /**
    * Invoice print
    */
    public function invoicePrint($orderNo,$invoiceNo){

        $data['taxInfo'] = $this->sale->calculateTaxRow($invoiceNo);
        $data['saleDataInvoice'] = DB::table('sales_orders')
                    ->where('order_no', '=', $invoiceNo)
                    ->leftJoin('location','location.loc_code','=','sales_orders.from_stk_loc')
                    ->leftJoin('invoice_payment_terms','invoice_payment_terms.id','=','sales_orders.payment_term')
                    ->select("sales_orders.*","location.location_name",'invoice_payment_terms.days_before_due')
                    ->first(); 
       // d($data['saleDataInvoice'],1);                   
        $data['invoiceData'] = $this->order->getSalseOrderByID($invoiceNo,$data['saleDataInvoice']->from_stk_loc);

        $data['customerInfo']  = DB::table('sales_orders')
                             ->where('sales_orders.order_no',$orderNo)
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                             ->leftjoin('cust_branch','cust_branch.branch_code','=','sales_orders.branch_id')
                             ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','countries.country','cust_branch.billing_country_id')                            
                             ->first();
        $data['orderInfo']  = DB::table('sales_orders')->where('order_no',$orderNo)->select('reference','order_no')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['saleDataInvoice']->days_before_due."days")));
        //return view('admin.invoice.invoicePdf', $data);
        $pdf = PDF::loadView('admin.invoice.invoicePrint', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('invoice_'.time().'.pdf',array("Attachment"=>0));        
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
                             ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','countries.country','cust_branch.billing_country_id')                            
                             ->first();
        $data['orderInfo']  = DB::table('sales_orders')->where('order_no',$orderNo)->select('reference','order_no')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['saleDataInvoice']->days_before_due."days")));
        //return view('admin.invoice.invoicePdf', $data);
        $pdf = PDF::loadView('admin.invoice.invoicePdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('invoice_'.time().'.pdf',array("Attachment"=>0));        
    }

    public function destroy($id)
    {
        
        if(isset($id)) {
            $record = \DB::table('sales_orders')->where('order_no', $id)->first();
            if($record) {
                
                $invoice_id = $id;
                $order_id = $record->order_reference_id;
                $invoice_reference = $record->reference;
                $order_reference = $record->order_reference;

                DB::table('sales_orders')->where('order_no', '=', $invoice_id)->delete();
                DB::table('sales_order_details')->where('order_no', '=', $invoice_id)->delete();
                DB::table('stock_moves')->where('reference', '=', 'store_out_'.$invoice_id)->delete();
                DB::table('payment_history')->where('invoice_reference', '=', $invoice_reference)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('sales/list');
            }
        }
    }


}
