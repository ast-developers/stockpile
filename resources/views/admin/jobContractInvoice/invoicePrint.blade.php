<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Invoice</title>
</head>
<style>
 body{ font-family:DejaVu Sans, sans-serif; color:#121212; line-height:20px;}
 table, tr, td{
    border-bottom: 1px solid #d1d1d1;
    padding: 6px 0px;
}
tr{ height:40px;}
</style>
<body>
  <div style="width:900px; margin:15px auto;">

      <div style="float:left; margin-top:-50px;height:50px;">
          <div style="margin-left:-14px;font-size:30px; font-weight:bold; color:#383838;"><img src="{{asset('public/img/judea-mini.png')}}" style="width:200px; height: 100px;" alt=""/></div>
      </div>

    <div style="width:450px; float:left; margin-top:70px;height:50px;">
   <div style="font-size:30px; font-weight:bold; color:#383838;">Invoice</div>
  </div>
  <div style="width:450px; float:right;height:50px;margin-top:70px">
    <div style="text-align:right; font-size:14px; color:#383838;"><strong></strong></div>
    <div style="text-align:right; font-size:14px; color:#383838;"><strong></strong></div>
  </div>
  <div style="clear:both;"></div>

  <div style="margin-top:40px;height:125px;">
    <div style="width:400px; float:left; font-size:15px; color:#383838; font-weight:400;">
      <div><strong>{{ Session::get('company_name') }}</strong></div>
    <div>{{ Session::get('company_street') }}</div>
    <div>{{ Session::get('company_city') }}, {{ Session::get('company_state') }}</div>
    <div>{{ Session::get('company_country_id') }}, {{ Session::get('company_zipCode') }}</div>
    <div>+{{ "(".substr(Session::get('company_phone'), 0, 2).") ". substr(Session::get('company_phone'), 2, 9)}}</div>
    <div>{{ Session::get('site_short_name') }}</div>
    </div>
    <div style="width:300px; float:left;font-size:15px; color:#383838; font-weight:400;">
      <div><strong>Bill To</strong></div>
      <div>{{ !empty($customerInfo->name) ? $customerInfo->name : ''}}</div>
      <div>{{ !empty($customerInfo->billing_street) ? $customerInfo->billing_street : ''}}</div>
      <div>{{ !empty($customerInfo->billing_city) ? $customerInfo->billing_city : ''}}{{ !empty($customerInfo->billing_state) ? ', '.$customerInfo->billing_state : ''}}</div>
      <div>{{ !empty($customerInfo->billing_country_id) ? $customerInfo->billing_country_id : ''}}{{ !empty($customerInfo->billing_zip_code) ? ' ,'.$customerInfo->billing_zip_code : ''}}</div>
    </div>
    <div style="width:200px; float:left; text-align:right; font-size:15px; color:#383838; font-weight:400;">
      <div><strong>Invoice No # {{$contractDataInvoice->reference}}</strong></div>
      <div>Invocie Date : {{formatDate($contractDataInvoice->contract_date)}}</div>
      <div>Due Date : {{formatDate($due_date)}}</div>    
      @if($contractDataInvoice->total > 0)
      @if($contractDataInvoice->paid_amount == 0)
        <div style="font-weight:bold" >Status : Unpaid</div>
      @elseif($contractDataInvoice->paid_amount > 0 && $contractDataInvoice->total > $contractDataInvoice->paid_amount )
        <div style="font-weight:bold" >Status : Partially paid</div>
      @elseif($contractDataInvoice->total<=$saleDataInvoice->paid_amount)
        <div style="font-weight:bold" >Status : Paid</div>
      @endif

      @else
       <div style="font-weight:bold" >Status : Paid</div>
      @endif

    </div>
  </div>
  <div style="clear:both"></div>
  <div style="margin-top:30px;">
   <table style="width:100%; border-radius:2px; border:2px solid #d1d1d1; border-collapse: collapse;">
      <tr style="background-color:#f0f0f0; border-bottom:1px solid #d1d1d1; text-align:center; font-size:13px; font-weight:bold;">
      
      <td>S/N</td>
      <td>Item Name</td>
      <td>Quantity</td>
      <td>Price({{Session::get('currency_symbol')}})</td>
      <td>Tax(%)</td>
      <td>Discount(%)</td>
      <td style="padding-right:10px;text-align:right">Amount({{Session::get('currency_symbol')}})</td>
    
    </tr>

  <?php
    $taxAmount      = 0;
    $subTotalAmount = 0;
    $qtyTotal       = 0;
    $priceAmount    = 0;
    $discount       = 0;
    $discountPriceAmount = 0;  
    $sum = 0;
    $i=0;
  ?>
  @foreach ($invoiceData as $item)
   <?php
    $price = ($item['quantity']*$item['unit_price']);
    $discount =  ($item['discount_percent']*$price)/100;
    $discountPriceAmount = ($price-$discount);
    $qtyTotal +=$item['quantity']; 
    $subTotalAmount += $discountPriceAmount; 
   ?>
   @if($item['quantity']>0)

    <tr style="background-color:#fff; text-align:center; font-size:13px; font-weight:normal;">
      <td>{{++$i}}</td>
      <td>{{$item['description']}}</td>
      <td>{{$item['quantity']}}</td>
      <td>{{number_format(($item['unit_price']),2,'.',',')}}</td>
      <td>{{number_format($item['tax_rate'],2,'.',',')}}</td>
      <td>{{number_format($item['discount_percent'],2,'.',',')}}</td>
      <td style="padding-right:10px;text-align:right">{{number_format($discountPriceAmount,2,'.',',')}}</td>
    </tr>
  <?php
    $sum = $item['quantity']+$sum;
  ?>
  @endif
  @endforeach

       <?php
       $subTotalDiscount = ($subTotalAmount*$contractDataInvoice->discount_percent)/100;
       $subTotalAmount = $subTotalAmount-$subTotalDiscount;
       ?>

    <tr style="background-color:#fff; text-align:right; font-size:13px; font-weight:normal; height:100px;">
      <td colspan="6" style="border-bottom:none">
         Total Quantity<br />
       <strong>Discount(%)</strong><br/>
       <strong>SubTotal</strong><br/>
        </td>

      <td style="text-align:right; padding-right:10px;border-bottom:none">
        {{$sum}}<br />
       {{number_format(($contractDataInvoice->discount_percent),2,'.',',')}}<br/>
       {{Session::get('currency_symbol').number_format(($subTotalAmount),2,'.',',')}}<br/>
      </td>
    </tr>

    @foreach($taxInfo as $rate=>$tax_amount)
    @if($rate != 0)

    <?php
      $taxAmount += $tax_amount;
    ?>  
    <tr style="background-color:#fff; text-align:right; font-size:13px; font-weight:normal; height:100px;">
      <td colspan="6" style="border-bottom:none">
         Plus Tax({{$rate}}%)
        </td>
      <td style="text-align:right; padding-right:10px; border-bottom:none">
       {{$taxAmount}}
      </td>
    </tr>     
    @endif 
    @endforeach

    <tr style="background-color:#f0f0f0; text-align:right; font-size:13px; font-weight:normal;">
      <td colspan="6" style="text-align:right;border-bottom:none"><strong>Grand Total</strong></td>
      <td style="text-align:right; padding-right:10px;border-bottom:none"><strong>{{Session::get('currency_symbol').number_format(($subTotalAmount+$taxAmount),2,'.',',')}}</strong></td>
    </tr>
    <tr style="text-align:right; font-size:13px; font-weight:normal;">
      <td colspan="6" style="text-align:right;">Paid Amount</td>
      <td style="text-align:right; padding-right:10px;">{{Session::get('currency_symbol').number_format(($contractDataInvoice->paid_amount),2,'.',',')}}</td>
    </tr>
    <tr style="background-color:#f0f0f0; text-align:right; font-size:13px; font-weight:normal;">
      <td colspan="6" style="text-align:right;"><strong>Due Amount</strong></td>
      <td style="text-align:right; padding-right:10px"><strong>
        @if(($subTotalAmount+$taxAmount-$contractDataInvoice->paid_amount)< 0)
        -{{Session::get('currency_symbol').number_format(abs($subTotalAmount+$taxAmount-$contractDataInvoice->paid_amount),2,'.',',')}}
       @else
       {{Session::get('currency_symbol').number_format(abs($subTotalAmount+$taxAmount-$contractDataInvoice->paid_amount),2,'.',',')}}
       @endif
       </strong></td>
    </tr>
   </table>
    </div>
  <script type="text/javascript">
      window.onload = function() { window.print(); }
 </script>    
  </div>
</body>
</html>
