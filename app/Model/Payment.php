<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $table = 'payment_history';

  /**
  * Update order table with invoice payment
  * @invoice_reference
  */
  public function updatePayment($reference,$amount){

    $currentAmount = DB::table('sales_orders')->where('reference',$reference)->select('paid_amount')->first();
    $sum = ($currentAmount->paid_amount + $amount);
    DB::table('sales_orders')->where('reference',$reference)->update(['paid_amount' => $sum]); 
    return true;
  }

  public function getAllPaymentByUserId($from, $to, $customer, $id){
      $from = DbDateFormat($from);
      $to = DbDateFormat($to);
     if($customer == 'all'){
     $data =  DB::table('payment_history')
           ->leftjoin('debtors_master','debtors_master.debtor_no','=','payment_history.customer_id')
           ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
           ->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
           ->select('payment_history.*','debtors_master.name','payment_terms.name as pay_type','sales_orders.order_no as invoice_id','sales_orders.order_reference_id as order_id')
           ->where('payment_history.person_id',$id)
           ->whereDate('payment_history.payment_date','>=', $from)
           ->whereDate('payment_history.payment_date','<=', $to)
           ->orderBy('payment_history.payment_date','DESC')
           ->get();
          }else if($customer != 'all'){
     $data =  DB::table('payment_history')
           ->leftjoin('debtors_master','debtors_master.debtor_no','=','payment_history.customer_id')
           ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
           ->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
           ->select('payment_history.*','debtors_master.name','payment_terms.name as pay_type','sales_orders.order_no as invoice_id','sales_orders.order_reference_id as order_id')
           ->where(['payment_history.person_id'=>$id,'payment_history.customer_id'=>$customer])
           ->whereDate('payment_history.payment_date','>=', $from)
           ->whereDate('payment_history.payment_date','<=', $to)           
           ->orderBy('payment_history.payment_date','DESC')
           ->get();
          }
      return $data;

  }
  /**
  * Filter the payment history
  * @$from, $to, $customer, $payment_method
  */

   public function paymentFilter($from, $to, $customer, $payment_method){
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);
        $conditions = array();
        
        if($customer){
          $conditions['customer_id'] = $customer;
        }
        if($payment_method){
          $conditions['payment_type_id'] = $payment_method;
        }

        $data = DB::table('payment_history')
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','payment_history.customer_id')
                             ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                             ->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
                             ->select('payment_history.*','debtors_master.name','payment_terms.name as pay_type','sales_orders.order_no as invoice_id','sales_orders.order_reference_id as order_id')
                             ->where('payment_history.payment_date','>=',$from)
                             ->where('payment_history.payment_date','<=',$to)
                             ->where($conditions)
                             ->orderBy('payment_history.payment_date','DESC')
                             ->get();   
        return $data; 
   }


}
