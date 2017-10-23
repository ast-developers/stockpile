<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sales History Report</title>
</head>
<style>
 body{ font-family:Arial, Helvetica, sans-serif; color:#121212; line-height:22px;}

.page-break {
    page-break-after: always;
}

 table, tr, td{
    border-bottom: 1px solid #d1d1d1;
    padding: 6px 0px;
}
tr{ height:40px;}

</style>
<body>

  <div style="width:1040px; margin:0px auto;">
  <div style="height:130px">
    <div style="width:70%; float:left; font-size:15px; color:#383838; font-weight:400;">
      <div style="font-size:20px;"><strong>Sales History Report</strong></div>
      <div>Customer : {{$customerName}}</div>
      <div>Print date : {{formatDate(date('d-m-Y'))}}</div>
      <div>From : {{formatDate($fromDate)}}</div>
      <div>To : {{formatDate($toDate)}}</div>

    </div>

    <div style="width:30%; float:left;font-size:15px; color:#383838; font-weight:400;">
    <div><strong>{{ Session::get('company_name') }}</strong></div>
    <div>{{ Session::get('company_street') }}</div>
    <div>{{ Session::get('company_city') }}, {{ Session::get('company_state') }}</div>
    <div>{{ Session::get('company_country_id') }}, {{ Session::get('company_zipCode') }}</div>
    </div>
  </div>
 <div> 
 <table style="width:100%; border-radius:2px; border:2px solid #d1d1d1; border-collapse: collapse;">
 <tr style="background-color:#f0f0f0; border-bottom:1px solid #d1d1d1; text-align:center; font-size:13px; font-weight:bold;">
   <td>Order No</td>
   <td width="10%">Date</td>
   <td>Customer</td>
   <td>Quantity</td>
   <td>Sales({{Session::get('currency_symbol')}})</td>
   <td>Cost({{Session::get('currency_symbol')}})</td>
   <td>Tax({{Session::get('currency_symbol')}})</td>
   <td>Profit({{Session::get('currency_symbol')}})</td>
   <td>Profit(%)</td>
 </tr>

      <?php
        $qty = 0;
        $sales_price = 0;
        $purchase_price = 0;
        $tax = 0;
        $total_profit = 0;
        $count = 0;
      ?>
      @foreach ($itemList as $item)
      <?php
     
      $profit = ($item->sales_price_total-$item->purch_price_amount-$item->tax);
      if($item->purch_price_amount<=0){
        $profit_margin = 100;
      }else{
      $profit_margin = ($profit*100)/$item->purch_price_amount;
    }

      $qty += $item->qty;
      $sales_price += $item->sales_price_total;
      $purchase_price += $item->purch_price_amount;
      $tax += $item->tax;
      $total_profit += $profit;

      ?>

<tr style="background-color:#fff; text-align:center; font-size:13px; font-weight:normal;">
  
  <td>{{ $item->order_reference }}</td>
  <td>{{ formatDate($item->ord_date) }}</td>
  <td>{{ $item->name }}</td>
  <td>{{ $item->qty }}</td>
  <td>{{ number_format(($item->sales_price_total-$item->tax),2,'.',',') }}</td>

  <td>{{ number_format(($item->purch_price_amount),2,'.',',') }}</td>
  <td>{{ number_format(($item->tax),2,'.',',') }}</td>

  <td>{{ number_format(($profit),2,'.',',') }}</td>
  <td>{{ number_format(($profit_margin),2,'.',',') }}</td>
 </tr>
  @endforeach 

 <tr style="background-color:#f0f0f0; text-align:right; font-size:13px; font-weight:normal;">
  <th colspan="3" align="right">Total</th>
  <th>{{ $qty }}</th>
  <th>{{ $sales_price-$tax }}</th>
  <th>{{ number_format(($purchase_price),2,'.',',') }}</th>
  <th>{{ number_format(($tax),2,'.',',') }}</th>
  <th>{{ number_format(($total_profit),2,'.',',') }}</th>
  <th>&nbsp;</th>
 </tr>
</table>
  </div>
  </div>
</body>
</html>