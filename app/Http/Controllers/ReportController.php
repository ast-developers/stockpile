<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use App\Model\Orders;
use App\Http\Requests;
use App\Model\Sales;
use App\Model\Shipment;
use App\Model\Report;
use App\Model\Purchase;
use DB;
use Session;
use PDF;
use Excel;

class ReportController extends Controller
{
    public function __construct(Orders $orders,Sales $sales,Shipment $shipment,EmailController $email, Report $report, Purchase $purchase){
     
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
        $this->order = $orders;
        $this->sale = $sales;
        $this->shipment = $shipment;
        $this->email = $email;
        $this->report = $report;
        $this->purchase = $purchase;
    }

    /**
    * Return inventory Stock On Hand
    */
    public function inventoryStockOnHand(){
        
        $data['type'] = 'all';
        $data['location_id'] = 'all';
        $qtyOnHand = 0;
        $costValueQtyOnHand = 0;
        $retailValueOnHand = 0;
        $profitValueOnHand = 0;
        $mac = 0;
        $data['menu']     = 'report';
        $data['sub_menu'] = 'report/inventory-stock-on-hand';

        if(isset($_GET['btn'])) {
            $type = $_GET['type'];
            $location = $_GET['location'];

            $data['type'] = $type;
            $data['location_id'] = $location;

            $data['itemList'] = $itemList = $this->report->getInventoryStockOnHand($type,$location); 
        }else{
             $data['itemList'] = $itemList = $this->report->getInventoryStockOnHand($data['type'],$data['location_id']);  
           }
 
        foreach ($itemList as $key => $item) {
            
            $qtyOnHand += $item->available_qty;
            
            if($item->received_qty !=0){
               $mac = $item->cost_amount/$item->received_qty;
            }

            $costValueQtyOnHand += $item->available_qty*$mac;
            $retailValueOnHand += $item->available_qty*$item->retail_price;
            $profitValueOnHand += (($item->available_qty*$item->retail_price)-($item->available_qty*$mac));

        }
        $data['qtyOnHand'] = $qtyOnHand;
        $data['costValueQtyOnHand'] = $costValueQtyOnHand;
        $data['retailValueOnHand'] = $retailValueOnHand;
        $data['profitValueOnHand'] = $profitValueOnHand;
 
        $data['locationList']      = DB::table('location')->get();
        $data['categoryList']       = DB::table('stock_category')->get();

        return view('admin.report.inventory_stock_on_hand', $data);

    }

    /**
    * Return inventory Stock On Hand with pdf format
    */
    public function inventoryStockOnHandPdf(){

        $data['type'] = 'all';
        $data['location_id'] = 'all';
        $qtyOnHand = 0;
        $costValueQtyOnHand = 0;
        $retailValueOnHand = 0;
        $profitValueOnHand = 0;
        $mac = 0;

        if(isset($_GET)) {
            $type = $_GET['type'];
            $location = $_GET['location'];

            $data['type'] = $type;
            $data['location_id'] = $location;

            $data['itemList'] = $itemList = $this->report->getInventoryStockOnHand($type,$location); 
            $locationName = DB::table('location')->where('loc_code',$location)->first();
            $categoryName = DB::table('stock_category')->where('category_id',$type)->first();

            if($type=='all' && $location=='all'){
                $data['location_name'] = 'All Location'; 
                $data['category_name'] = 'All Category';
            }elseif($type !='all' && $location !='all'){
                $data['location_name'] = $locationName->location_name; 
                $data['category_name'] = $categoryName->description;
           }
            elseif($type =='all' && $location !='all'){
                $data['location_name'] = $locationName->location_name; 
                $data['category_name'] = 'All Category';
           }
            elseif($type !='all' && $location =='all'){
                $data['location_name'] = 'All Location'; 
                $data['category_name'] = $categoryName->description;
           }


        }
 
        foreach ($itemList as $key => $item) {
            
            $qtyOnHand += $item->available_qty;
            
            if($item->received_qty !=0){
               $mac = $item->cost_amount/$item->received_qty;
            }

            $costValueQtyOnHand += $item->available_qty*$mac;
            $retailValueOnHand += $item->available_qty*$item->retail_price;
            $profitValueOnHand += (($item->available_qty*$item->retail_price)-($item->available_qty*$mac));

        }
        $data['qtyOnHand'] = $qtyOnHand;
        $data['costValueQtyOnHand'] = $costValueQtyOnHand;
        $data['retailValueOnHand'] = $retailValueOnHand;
        $data['profitValueOnHand'] = $profitValueOnHand;
 
        $data['locationList']      = DB::table('location')->get();
        $data['categoryList']       = DB::table('stock_category')->get();
        $data['menu']     = 'report';
        $data['sub_menu'] = 'report/inventory-stock-on-hand';
        //d($data['locationList'],1);
        $pdf = PDF::loadView('admin.report.inventory_item_stock_on_hand_pdf', $data);
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('inventory_stock_on_hand_'.time().'.pdf',array("Attachment"=>0)); 

    }

    /**
    * Return inventory Stock On Hand with csv format
    */
    public function inventoryStockOnHandCsv()
    {
            
            $type = $_GET['type'];
            $location = $_GET['location'];

            $itemList = $this->report->getInventoryStockOnHand($type,$location);
            foreach ($itemList as $key => $value) {
                $mac = 0;
                $profit_margin = 0;
                if($value->received_qty !=0){
                 $mac = $value->cost_amount/$value->received_qty;
                }
                $in_value = $value->available_qty*$mac;
                $retail_value = $value->available_qty*$value->retail_price;
                $profit_value = ($retail_value-$in_value);
                if($in_value !=0){
                $profit_margin = ($profit_value*100/$in_value); 
                }

                $data[$key]['Product'] = $value->description;
                $data[$key]['Stock Id'] = $value->stock_id;
                $data[$key]['In Stock'] = $value->available_qty;
                $data[$key]['MAC'] = Session::get('currency_symbol').number_format($mac,2,'.',',');
                $data[$key]['Retail Price'] = Session::get('currency_symbol').number_format($value->retail_price,2,'.',',');
                $data[$key]['In Value'] = Session::get('currency_symbol').number_format($in_value,2,'.',',');
                $data[$key]['Retail value'] = Session::get('currency_symbol').number_format($retail_value,2,'.',',');
                $data[$key]['Profit Value'] = Session::get('currency_symbol').number_format($profit_value,2,'.',',');
                $data[$key]['Profit margin'] = number_format($profit_margin,2,'.',',');
            }

        return Excel::create('inventory_stock_on_hand_'.time().'', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            }); 
        })->download();
    }

    /**
    * Sales history report
    */
     public function salesReport(){
        $data['menu']     = 'report';
        $data['sub_menu'] = 'report/sales-report';

        $data['searchType'] = $type = 'daily';
        $data['yearList'] = $this->sale->getSaleYears();
        $data['year'] = NULL;
        $data['month'] = NULL;

        $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : '';
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : '';
        $data['item'] = $item = isset($_GET['product']) ? $_GET['product'] : '';
        
        $data['customerList'] = DB::table('debtors_master')->select('debtor_no','name')->where(['inactive'=>0])->get();
        $data['locationList'] = DB::table('location')->select('loc_code','location_name')->get();
        $data['productList'] = DB::table('item_code')->where(['inactive'=>0,'deleted_status'=>0])->select('stock_id','description')->get();

        $data['from'] = $from = formatDate(date("d-m-Y", strtotime("-1 months")));
        $data['to'] = $to = formatDate(date('d-m-Y'));

        if(isset($_GET['btn'])){
            $to = DbDateFormat($_GET['to']);
            $from = DbDateFormat($_GET['from']);
            $data['searchType'] = $type = $_GET['searchType'];
            $data['year'] = $year = $_GET['year'];
            $data['month'] = $month = $_GET['month'];

            $data['itemList'] = $list = $this->report->getSalesReport($type, $from, $to,$year,$month, $item, $customer, $location);
           $data['from'] = formatDate($from);
           $data['to'] = formatDate($to);

        }else{
           $data['itemList'] = $list =  $this->report->getSalesReport($type="daily", $from, $to,$year=NULL,$month=NULL, $item='all', $customer='all', $location='all');
        }
     
//d($list,1);
        $cost['purchase'] = array();
        $sale['sale'] = array();
        $profit['profit'] = array();
        $quantity['quantity'] = array();
        
        $date['date'] = array();
        foreach ($list as $key => $value) {
           if( $type=='daily' || $type=='custom' ){
           $date['date'][$key] = date('d-m-Y',strtotime($value->ord_date));
           }elseif($type=='monthly'){
            $date['date'][$key] = date('F-Y',strtotime($value->ord_date));
           }elseif($type=='yearly' && $year =='all'){
            $date['date'][$key] = date('Y',strtotime($value->ord_date));
           }elseif($type=='yearly' && $year !='all' && $month == 'all'){
            $date['date'][$key]= date('F-Y',strtotime($value->ord_date));
           }elseif($type=='yearly' && $year !='all' && $month != 'all'){
            $date['date'][$key] = date('d-m-Y',strtotime($value->ord_date));
           }
          $sale['sale'][$key] = round($value->sale,2);
          $cost['purchase'][$key] = round($value->purchase,2);
          $profit['profit'][$key] = round(($value->sale-$value->purchase));
          $quantity['quantity'][$key] = round($value->qty);
        }

        $data['sale'] = json_encode($sale['sale']);
        $data['purchase'] = json_encode($cost['purchase']);
        $data['profit_amount'] = json_encode($profit['profit']);
        $data['date']   =  json_encode($date['date']);
        $data['quantity']   =  json_encode($quantity['quantity']); 
//$data['profit'],1);
      return view('admin.report.sales_report', $data);  
    }

    /**
    * Sales report on csv
    */

    public function salesReportCsv(){
        $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : NULL;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : NULL;
        $data['product'] = $item = isset($_GET['product']) ? $_GET['product'] : NULL;
        
        $to = isset($_GET['to']) ? DbDateFormat($_GET['to']) : NULL;
        $from = isset($_GET['from']) ? DbDateFormat($_GET['from']) : NULL;
        $data['type'] = $type = isset($_GET['searchType']) ? $_GET['searchType'] : NULL;
        $data['year'] = $year = isset($_GET['year']) ? $_GET['year'] : NULL;
        $data['month'] = $month = isset($_GET['month']) ? $_GET['month'] : NULL;

        $itemList =  $this->report->getSalesReport($type, $from, $to,$year,$month, $item, $customer, $location);

        foreach($itemList as $key => $value){
            if( $type == 'custom' || $type == 'daily')
            {
            $datas[$key]['Date'] = date('d-m-Y',strtotime($value->ord_date));
            }elseif($type == 'monthly')
            {
                $datas[$key]['Month'] = date('F-Y',strtotime($value->ord_date));
            }elseif($type == 'yearly')
            {
              if($year=='all'){
                $datas[$key]['Year'] = date('Y',strtotime($value->ord_date));
              }else if($year !='all' && $month == 'all'){
                $datas[$key]['Month'] = date('F-Y',strtotime($value->ord_date));
              }else if($year !='all' && $month != 'all'){
                $datas[$key]['Date'] = date('d-m-Y',strtotime($value->ord_date));
              }

            }

                $datas[$key]['No Of Order'] = $value->total_order;
                $datas[$key]['Sales Volume'] = $value->qty;
                $datas[$key]['Sales Value'] = $value->sale;
                $datas[$key]['Cost Value'] = $value->purchase;
                $datas[$key]['Profit'] = $value->sale-$value->purchase;
            }

        return Excel::create('sales_report_'.time().'', function($excel) use ($datas) {
            $excel->sheet('mySheet', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            }); 
        })->download(); 
    }

    /**
    * Sales report on pdf
    */

    public function salesReportPdf(){
        $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : NULL;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : NULL;
        $data['product'] = $item = isset($_GET['product']) ? $_GET['product'] : NULL;
        
        $to = isset($_GET['to']) ? DbDateFormat($_GET['to']) : NULL;
        $from = isset($_GET['from']) ? DbDateFormat($_GET['from']) : NULL;
        $data['type'] = $type = isset($_GET['searchType']) ? $_GET['searchType'] : NULL;
        $data['year'] = $year = isset($_GET['year']) ? $_GET['year'] : NULL;
        $data['month'] = $month = isset($_GET['month']) ? $_GET['month'] : NULL;

        $data['itemList'] =  $this->report->getSalesReport($type, $from, $to,$year,$month, $item, $customer, $location);

        $data['date_range'] =  formatDate($from) .' To '. formatDate($to); 
        $pdf = PDF::loadView('admin.report.sales_report_pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('sales_report_'.time().'.pdf',array("Attachment"=>0));  
    }
    /**
    * Sales report by date
    */
    public function salesReportByDate($date){
        $data['menu']     = 'report';
        $data['sub_menu'] = 'report/sales-report';
        $data['itemList'] =  $this->report->getSalesReportByDate(date('Y-m-d',$date));
        $data['date'] = $date;
        $data['reportDate'] = date('d-m-Y',$date);
        return view('admin.report.sales_report_by_date', $data);
    }
    /**
    * Sales report by date on csv
    */
    public function salesReportByDateCsv($date){
        $itemList =  $this->report->getSalesReportByDate(date('Y-m-d',$date));

        foreach ($itemList as $key => $item) {

                $profit = ($item->sales_price_total-$item->tax-$item->purch_price_amount);
                if($item->purch_price_amount<=0){
                
                $profit_margin = 100;
                }else{
                    $profit_margin = ($profit*100)/$item->purch_price_amount;
                }
                
                $data[$key]['Order No'] = $item->order_reference;
                $data[$key]['Date'] = formatDate($item->ord_date);
                $data[$key]['Customer'] = $item->name;
                $data[$key]['Qty'] = $item->qty;
                $data[$key]['Sales Value('.Session::get('currency_symbol').')'] = $item->sales_price_total-$item->tax;
                $data[$key]['Cost Value('.Session::get('currency_symbol').')'] = $item->purch_price_amount;
                $data[$key]['Tax('.Session::get('currency_symbol').')'] = $item->tax;
                $data[$key]['Profit('.Session::get('currency_symbol').')'] = $profit;
                $data[$key]['Profit Margin(%)'] = number_format(($profit_margin),2,'.',',');
            }

        return Excel::create('sales_report_by_date_'.time().'', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            }); 
        })->download(); 

    }
    /**
    * Sales report by date on pdf
    */
    public function salesReportByDatePdf($date){
        $data['menu']     = 'report';
        $data['sub_menu'] = 'sales-report';
        $data['itemList'] =  $this->report->getSalesReportByDate(date('Y-m-d',$date));
        $data['reportDate'] = date('d-m-Y',$date);
        $pdf = PDF::loadView('admin.report.sales_report_by_date_pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('sales_report_by_date_'.time().'.pdf',array("Attachment"=>0)); 

    }
    /**
    * Sales history report
    */

    public function salesHistoryReport(){

        $data['customerList'] = DB::table('debtors_master')->where(['inactive'=>0])->get();
        $data['menu']     = 'report';
        $data['sub_menu'] = 'sales-history-report';

        if(isset($_GET['btn'])){
           $user = $_GET['customer'];
           $from = date('Y-m-d',strtotime($_GET['from']));
           $to   = date('Y-m-d',strtotime($_GET['to'])); 
           $data['from'] =  formatDate($_GET['from']);
           $data['to']   =  formatDate($_GET['to']);
           $data['user'] = $user;
           $data['itemList'] =  $this->report->getSalesHistoryReport($from,$to,$user);
        }else{
           $data['user'] = 'all';
           $data['itemList'] =  $this->report->getSalesHistoryReport($from=NULL,$to=NULL,$user=NULL);
          
           $from = DB::table('sales_orders')->select('ord_date')->where('trans_type',SALESINVOICE)->orderBy('ord_date','asc')->first();
           $to = DB::table('sales_orders')->select('ord_date')->where('trans_type',SALESINVOICE)->orderBy('ord_date','desc')->first();
          if(!empty($from) && !empty($to)){
           $data['from'] = formatDate($from->ord_date);
           $data['to'] = formatDate($to->ord_date);
          }

        }
        return view('admin.report.sales_history_report', $data);
    }

    /**
    * Sales history report on pdf
    */

    public function salesHistoryReportPdf(){

        $to = DbDateFormat($_GET['to']);
        $from = DbDateFormat($_GET['from']);
        $user = $_GET['customer'];
        if($user !='all'){
        $customer = DB::table('debtors_master')->where('debtor_no',$user)->first();
        $data['customerName'] = $customer->name;
       }else{
        $data['customerName'] = 'All';
       }
        
        $data['fromDate'] = $_GET['from'];
        $data['toDate'] = $_GET['to'];

        $data['itemList'] =  $this->report->getSalesHistoryReport($from,$to,$user);
        $pdf = PDF::loadView('admin.report.sales_history_report_pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('sales_history_report_'.time().'.pdf',array("Attachment"=>0)); 
    }

    /**
    * Sales history report on csv
    */

    public function salesHistoryReportCsv(){
        $to = DbDateFormat($_GET['to']);
        $from = DbDateFormat($_GET['from']);
        $user = $_GET['customer'];

        $itemList =  $this->report->getSalesHistoryReport($from,$to,$user);

        foreach ($itemList as $key => $item) {

                $profit = ($item->sales_price_total-$item->purch_price_amount-$item->tax);
                if($item->purch_price_amount<=0){
                $profit_margin = 100;
                }else{
                $profit_margin = ($profit*100)/$item->purch_price_amount;
               }
                $data[$key]['Order No'] = $item->order_reference;
                $data[$key]['Date'] = formatDate($item->ord_date);
                $data[$key]['Customer'] = $item->name;
                $data[$key]['Qty'] = $item->qty;
                $data[$key]['Sales Value('.Session::get('currency_symbol').')'] = $item->sales_price_total-$item->tax;
                $data[$key]['Cost Value('.Session::get('currency_symbol').')'] = $item->purch_price_amount;
                $data[$key]['Tax('.Session::get('currency_symbol').')'] = $item->tax;
                $data[$key]['Profit('.Session::get('currency_symbol').')'] = $profit;
                $data[$key]['Profit Margin(%)'] = number_format(($profit_margin),2,'.',',');
            }

        return Excel::create('sales_history_report_'.time().'', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            }); 
        })->download(); 

    }

    /**
    * Purchase report
    */

    public function purchaseReport(){

        $data['menu']     = 'report';
        $data['sub_menu'] = 'purchase-report';
        $data['searchType'] = $type = 'daily';
        $data['yearList'] = $this->purchase->getPurchaseYears();
        $data['year'] = NULL;
        $data['month'] = NULL;

        $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : '';
        $data['supplier'] = $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : '';
        $data['item'] = $item = isset($_GET['product']) ? $_GET['product'] : '';
        
        $data['supplierList'] = DB::table('suppliers')->select('supplier_id','supp_name')->where(['inactive'=>0])->get();
        $data['locationList'] = DB::table('location')->select('loc_code','location_name')->get();
        $data['itemList'] = DB::table('item_code')->where(['inactive'=>0,'deleted_status'=>0])->select('stock_id','description')->get();

        $data['from'] = formatDate(date("d-m-Y", strtotime("-1 months")));
        $data['to'] = formatDate(date('d-m-Y'));

        if(isset($_GET['btn'])){
            
           $data['searchType'] = $type = $_GET['searchType'];

           $data['from'] = $from = $_GET['from'];
           $data['to'] = $to = $_GET['to'];
           
           if(isset($_GET['year'])){
              $data['year'] = $year = $_GET['year'];
            }else{
                $year = NULL; 
            }

           if(isset($from) && isset($to) && $type == 'custom'){
                $data['list'] = $list = $this->purchase->getPurchaseReport($type, DbDateFormat($from), DbDateFormat($to),$year=NULL,$month=NULL,$item,$supplier,$location);
           }else if($type == 'yearly'){
                $data['month'] = $month = isset($_GET['month']) ? $_GET['month'] : NULL; 
               
                if($year=='all'){

                $data['list'] = $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL,'all',$month,$item,$supplier,$location);
                $data['year'] = 'all';
                $data['searchType'] = 'yearly';
                $data['month'] = NULL;
                }elseif($year !='all' AND $month == 'all'){
             
                $data['list'] = $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year, 'all',$item,$supplier,$location);
                $data['searchType'] = 'yearly';


                }elseif($year !='all' AND $month != 'all'){
             
                $data['list'] = $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year, $month,$item,$supplier,$location);
                $data['searchType'] = 'yearly';

                }  

            }
            else if($type != 'yearly' && $type !='custom'){
           
                $data['list'] = $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL,$year=NULL,$month=NULL,$item,$supplier,$location);
                $data['month'] = NULL;
            }
           }else{
               $data['list'] = $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL,$year=NULL,$month=NULL,'all','all','all');
               $data['month'] = NULL;
           }
        
        $qty = 0;
        $cost = 0;
        $order = 0;
        
        $graph = array();
        foreach ($list as $key => $value) {
           $qty += $value->qty;
           $cost += $value->cost;
           $order += $value->order_total;
           if( $type=='daily' || $type=='custom' ){
           $graph[$key][0] = date('d-m-Y',strtotime($value->ord_date));
           }elseif($type=='monthly'){
            $graph[$key][0] = date('F-Y',strtotime($value->ord_date));
           }elseif($type=='yearly' && $year =='all'){
            $graph[$key][0] = date('Y',strtotime($value->ord_date));
           }elseif($type=='yearly' && $year !='all' && $month == 'all'){
            $graph[$key][0] = date('F-Y',strtotime($value->ord_date));
           }elseif($type=='yearly' && $year !='all' && $month != 'all'){
            $graph[$key][0] = date('d-m-Y',strtotime($value->ord_date));
           }
           $graph[$key][1] = (int)$value->cost;
        }

        $data['cost'] = $cost;
        $data['qty'] = $qty;
        $data['order'] = $order;
        $data['graph'] = json_encode($graph);
        return view('admin.report.purchase_report', $data);
    }

    /**
    * Purchase report pdf
    */

    public function purchaseReportPdf(){
        $type = $_GET['type'];
        
        $data['product'] = $item = $_GET['product'];
        $data['supplier'] = $supplier = $_GET['supplier'];
        $data['location'] = $location = $_GET['location'];

        if($type != 'custom' && $type !='yearly'){
            $data['list'] = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year=NULL, $month=NULL,$item,$supplier,$location);
        }else if($type =='custom' && $type !='yearly'){

            $from = $_GET['from'];
            $to = $_GET['to'];

            if($from == NULL || $to == NULL){
            $data['list'] = $this->purchase->getPurchaseReport('daily', $from=NULL, $to=NULL, $year=NULL, $month=NULL,$item,$supplier,$location);  
            }else{
            $data['list'] = $this->purchase->getPurchaseReport($type, DbDateFormat($from), DbDateFormat($to), $year=NULL, $month=NULL);                
            }  
        }else if($type =='yearly'){
            $year = isset($_GET['year']) ? $_GET['year'] : NULL;
            $month = isset($_GET['month']) ? $_GET['month'] : NULL;

                if($year=='all'){
                
                $data['list'] = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL,'all',$month,$item,$supplier,$location);
                
                $data['year'] = 'all';
                $data['month'] = $month;

                }elseif($year !='all' AND $month == 'all'){

                $data['list'] = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year, 'all',$item,$supplier,$location);
                
                $data['year'] = $year;
                $data['month'] = 'all';

                }elseif($year !='all' AND $month != 'all'){
                $data['list'] = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year, $month,$item,$supplier,$location);
                $data['year'] = $year;
                $data['month'] = $month;

                }

        }
       
        $data['type'] = $type;
        $data['date_range'] =  formatDate($from) .' To '. formatDate($to); 
        $pdf = PDF::loadView('admin.report.purchase_report_pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('purchase_report_'.time().'.pdf',array("Attachment"=>0));

    }

    /**
    * Purchase report pdf
    */

    public function purchaseReportCsv(){
        
        $type = $_GET['type'];
        $data['product'] = $item = $_GET['product'];
        $data['supplier'] = $supplier = $_GET['supplier'];
        $data['location'] = $location = $_GET['location'];

        if($type != 'custom' && $type !='yearly'){
            $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year=NULL, $month=NULL,$item,$supplier,$location);
        }else if($type =='custom' && $type !='yearly'){

            $from = $_GET['from'];
            $to = $_GET['to'];

            if($from == NULL || $to == NULL){
            $list = $this->purchase->getPurchaseReport('daily', $from=NULL, $to=NULL, $year=NULL, $month=NULL,$item,$supplier,$location);  
            }else{
            $list = $this->purchase->getPurchaseReport($type, DbDateFormat($from), DbDateFormat($to), $year=NULL, $month=NULL,$item,$supplier,$location);                
            }  
        }else if($type =='yearly'){
            $year = isset($_GET['year']) ? $_GET['year'] : NULL;
            $month = isset($_GET['month']) ? $_GET['month'] : NULL;

                if($year=='all'){
                
                $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL,'all',$month,$item,$supplier,$location);

                }elseif($year !='all' AND $month == 'all'){

                $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year, 'all',$item,$supplier,$location);

                }elseif($year !='all' AND $month != 'all'){
                $list = $this->purchase->getPurchaseReport($type, $from=NULL, $to=NULL, $year, $month,$item,$supplier,$location);
                }

        }
       
        $data = array();
        foreach ($list as $key => $value) {

            if( $type == 'custom' || $type == 'daily'){
            $data[$key]['Date'] = date('d-m-Y',strtotime($value->ord_date));
            }elseif($type == 'monthly'){
                $data[$key]['Month'] = date('F-Y',strtotime($value->ord_date));
            }elseif($type == 'yearly'){
              if($year=='all'){
                $data[$key]['Year'] = date('Y',strtotime($value->ord_date));
              }else if($year !='all' && $month == 'all'){
                $data[$key]['Month'] = date('F-Y',strtotime($value->ord_date));
              }else if($year !='all' && $month != 'all'){
                $data[$key]['Date'] = date('d-m-Y',strtotime($value->ord_date));
              }

            }
            
            $data[$key]['Order'] = $value->order_total;
            $data[$key]['Quantity'] = $value->qty;
            $data[$key]['Cost'] = $value->cost;
        }


        return Excel::create('purchase_report_'.time().'', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            }); 
        })->download(); 


    }

    public function purchaseReportDateWise($time){
        $data['menu']     = 'report';
        $data['sub_menu'] = 'purchase-report';
        $date = DbDateFormat(date('d-m-Y',$time));
    
        $data['purchData'] = $this->purchase->getPurchaseReportDateWise($date);
        //d($data,1);
        $data['date'] = formatDate(date('d-m-Y',$time));
        return view('admin.report.purchase_report_datewise', $data);
        
    }

}
