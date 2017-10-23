<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use session;
use App\Model\Report;

class DashboardController extends Controller
{
    public function __construct(Report $report){
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
        $this->report = $report;
    }
    
	protected $data = [];

    /**
     * Display a listing of the Over All Information on Dashboard.
     *
     * @return Dashboard page view
     */
	
    public function index()
    {   $date = array();
        $sale['sale'] = array();
        $cost['cost'] = array();
        $profit['profit'] = array();
        $sale['sale'] = array();
        $data['startDate'] = formatDate(date('j M Y')); 
        $startDate = formatDate(date('j M Y'));
        // Report on graph chart
        $str = '[';
        $i=0;
        $count = 0;
        $scp = $this->report->getSalesCostProfit();

        foreach ($scp as $key => $value) {
         if($count==0){
            $startDate =  formatDate($value->ord_date);
         }
          $sale['sale'][$key] = round($value->sale,2);
          $cost['cost'][$key] = round($value->cost,2);
          $profit['profit'][$key] = round($value->profit,2);
         
         $date[$key] = formatDate($value->ord_date);
            $count++;
        }
        // Get total revenue and profit
        $salesHistory = $this->report->getSalesHistoryReport($from=NULL,$to=NULL,$user=NULL);
        //d($salesHistory,1);
        $totalSale = 0;
        $totalCost = 0;
        $totalSoldQty = 0;
        $tax = 0;
        foreach ($salesHistory as $key => $history) {
            $totalSale += $history->sales_price_total-$history->tax;
            $totalCost += $history->purch_price_amount;
            $totalSoldQty += $history->qty;
            $tax += $history->tax;
        }
        
        // Get list of order to invoice
        $data['orderToInvoiceList'] = $this->report->orderToInvoiceList();
        $data['orderToshipmentList'] = $this->report->orderToshipmentList();
        $data['latestInvoicesList'] = $this->report->latestInvoicesList();
        $data['latestPaymentList'] = $this->report->latestPaymentList();
        
        $data['sale'] = json_encode($sale['sale']);
        $data['cost'] = json_encode($cost['cost']);
        $data['profit'] = json_encode($profit['profit']);
      	
        $data['date']   =  json_encode($date);  
      //  d($data['date'],1);
        $data['startDate'] = $startDate;
        $data['endDate']   = formatDate(date('j M Y'));
        $data['totalSoldQty']   = $totalSoldQty; 
        $data['revenueTotal']   = $totalSale;
        $data['profitTotal']    = ($totalSale-$totalCost);
        $data['stockOnHandTotal']    = $totalSoldQty;  
        $data['menu'] = 'dashboard';       
        return view('admin.dashboard', $data);
    }

    /**
     * Change Language function
     *
     * @return true or false
     */

    public function switchLanguage(Request $request)
    {
        
        if ($request->lang) {
            \Session::put('dflt_lang', $request->lang);
            //\DB::table('preference')->where('id', 1)->update(['value' => $request->lang]);
            \App::setLocale($request->lang);
            echo 1;
        } else {
            echo 0;
        }

    }
}
