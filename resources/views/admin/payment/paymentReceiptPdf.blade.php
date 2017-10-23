<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Payment | Information</title>
</head>
<style>
 body{ font-family:Arial, Helvetica, sans-serif; color:#121212; line-height:22px;}
 table, tr, td{
    border-bottom: 1px solid #d1d1d1;
    padding: 6px 0px;
}
tr{ height:40px;}
</style>
<body>
  <div style="width:900px; margin:15px auto; padding-bottom:40px;">
    <div style="font-weight:bold;font-size:30px;">Payment</div>
    <div style="width:300px; float:left;">
      <div style="margin-top:20px;">
        <div style="font-size:16px; color:#000000; font-weight:bold;">{{ Session::get('company_name') }}</div>
        <div style="font-size:16px;">{{ Session::get('company_street') }}</div>
        <div style="font-size:16px;">{{ Session::get('company_city') }}, {{ Session::get('company_state') }}</div>
        <div style="font-size:16px;">{{ Session::get('company_country_id') }}, {{ Session::get('company_zipCode') }}</div>
      </div>
    </div>

    <div style="width:300px; float:left;">
      <div style="margin-top:20px;">
        <div style="font-size:16px;"><strong>{{ !empty($paymentInfo->name) ? $paymentInfo->name : '' }}</strong></div>
        <div style="font-size:16px;">{{ !empty($paymentInfo->billing_street) ? $paymentInfo->billing_street : '' }}</div>
        <div style="font-size:16px;">{{ !empty($paymentInfo->billing_city) ? $paymentInfo->billing_city : '' }}{{ !empty($paymentInfo->billing_state) ? ', '.$paymentInfo->billing_state : '' }}</div>
        <div style="font-size:16px;">{{ !empty($paymentInfo->billing_country_id) ? $paymentInfo->billing_country_id : '' }}{{ !empty($paymentInfo->billing_zip_code) ? ', '.$paymentInfo->billing_zip_code: '' }}</div>
      </div>
      <br/>
    </div>
  
    <div style="width:300px; float:left;">
      <div style="margin-top:20px;">
      
       <div style="font-size:16px;">{{ trans('message.invoice.payment_no').' # '.sprintf("%04d", $paymentInfo->id) }}</div>
      </div>
      <br/>
    </div>

  <div style="clear:both"></div>
    <h3 style="text-align:center;margin:20px;0px;">PAYMENT RECEIPT</h3>
    <div>Payment Date : {{ formatDate($paymentInfo->payment_date) }}</div>
    <div>Payment Method : {{ $paymentInfo->payment_method }}</div>
    <br>
    <div style="height:100px;width:300px;background-color:#f0f0f0;color:#000;text-align:center;padding-top:30px">
      <strong>Total Amount</strong><br>
      <strong>{{ Session::get('currency_symbol').number_format($paymentInfo->amount,2,'.',',') }}</strong>
    </div>
    <div style="clear:both"></div>
    <br>
    
   <table style="width:100%; border-radius:2px; border:2px solid #d1d1d1; border-collapse: collapse;">
      <tr style="background-color:#f0f0f0; border-bottom:1px solid #d1d1d1; text-align:center; font-size:13px; font-weight:bold;">
      <td>Order No</td>
      <td>Invoice No</td>
      <td>Invoce Date</td>
      <td>Invoce Amount</td>
      <td>Paid Amount</td>
    </tr>
    <tr style="background-color:#fff; text-align:center; font-size:13px; font-weight:normal;">
      <td>{{ $paymentInfo->order_reference }}</td>
      <td>{{$paymentInfo->invoice_reference}}</td>
      <td>{{formatDate($paymentInfo->invoice_date)}}</td>
      <td>{{ Session::get('currency_symbol').number_format($paymentInfo->invoice_amount,2,'.',',') }}</td>
      <td>{{ Session::get('currency_symbol').number_format($paymentInfo->amount,2,'.',',') }}</td>
    </tr>
  </table>
  </div>
</body>
</html>