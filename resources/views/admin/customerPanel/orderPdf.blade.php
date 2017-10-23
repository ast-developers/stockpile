<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Order</title>
</head>
<style>
 body{ font-family:Arial, Helvetica, sans-serif; color:#121212; line-height:22px;}
</style>
<body>

  <div style="width:900px; margin:15px auto; padding-bottom:40px;">
    <div style="text-align:right; font-weight:bold;font-size:30px">Sales Order</div>
    <div style="text-align:right;">Order No # {{$saleData->reference}}</div>
    <div style="font-size:16px;text-align:right;"> Date : {{formatDate($saleData->ord_date)}}</div>

    <div style="width:300px; float:left;">
      <div style="margin-top:10px;">

        <div style="font-size:16px; color:#000000; font-weight:bold;">{{ $settings[8]->value }}</div>
        <div style="font-size:16px;">{{ $settings[11]->value }}</div>
        <div style="font-size:16px;">{{ $settings[12]->value }}, {{ $settings[13]->value }}</div>
        <div style="font-size:16px;">{{ $settings[15]->value }}, {{ $settings[14]->value }}</div>
        
      </div>
    </div>

    <div style="width:300px; float:left;">
      <div style="margin-top:10px; margin-bottom:15px;">
        <div style="font-size:16px; color:#000000; font-weight:bold;">Bill To</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->name) ? $customerInfo->name : ''}}</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->billing_street) ? $customerInfo->billing_street : ''}}</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->billing_city) ? $customerInfo->billing_city : ''}}{{ !empty($customerInfo->billing_state) ? ', '.$customerInfo->billing_state : ''}}</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->billing_country_id) ? $customerInfo->billing_country_id : ''}}{{ !empty($customerInfo->billing_zip_code) ? ' ,'.$customerInfo->billing_zip_code : ''}}</div>
      </div>
    </div>
    
    <div style="width:300px; float:left;">
      <div style="margin-top:10px; margin-bottom:15px;">
        <div style="font-size:16px; color:#000000; font-weight:bold;">Ship To</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->br_name) ? $customerInfo->br_name : ''}}</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->shipping_street) ? $customerInfo->shipping_street :'' }}</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->shipping_city) ? $customerInfo->shipping_city : ''}}{{ !empty($customerInfo->shipping_state) ? ', '.$customerInfo->shipping_state : ''}}</div>
        <div style="font-size:16px;">{{ !empty($customerInfo->shipping_country_id) ? $customerInfo->shipping_country_id :''}}{{ !empty($customerInfo->shipping_zip_code) ? ', '.$customerInfo->shipping_zip_code : ''}}</div>
      </div>
    </div>
  
  <div style="clear:both"></div>
    <div style="background-color:#323a45; padding:10px 20px;height:20px">
      <div style="width:50px;  color:#FFFFFF; float:left; font-weight:bold; text-align:center">S/N</div>
      <div style="width:210px; color:#FFFFFF; float:left; font-weight:bold; text-align:center">Item Description</div>
      <div style="width:100px; color:#FFFFFF; float:left; font-weight:bold; text-align:center">Quantity</div>
      <div style="width:100px; color:#FFFFFF; float:left; font-weight:bold; text-align:center">Price({{Session::get('currency_symbol')}})</div>
      <div style="width:100px; color:#FFFFFF; float:left; font-weight:bold; text-align:center">Tax(%)</div>
      <div style="width:100px; color:#FFFFFF; float:left; font-weight:bold; text-align:center">Discount(%)</div>
      <div style="width:200px; color:#FFFFFF; float:left; font-weight:bold; text-align:right">Amount({{Session::get('currency_symbol')}})</div>
      <div style="clear:both"></div>
    </div>

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
    <div style="padding:0px 20px; margin-top:15px;">
      <div style="width:50px; font-size:14px; float:left; text-align:center">{{++$i}}</div>
      <div style="width:210px; float:left; font-size:14px; text-align:center">{{$item['description']}}</div>
      <div style="width:100px; float:left; font-size:14px; text-align:center">{{$item['quantity']}}</div>
      <div style="width:100px; float:left; font-size:14px; text-align:center">{{number_format(($item['unit_price']),2,'.',',')}}</div>
      <div style="width:100px; float:left; font-size:14px; text-align:center">{{number_format($item['tax_rate'],2,'.',',')}}</div>
      <div style="width:100px; float:left; font-size:14px; text-align:center">{{number_format($item['discount_percent'],2,'.',',')}}</div>
      <div style="width:200px; float:left; font-size:14px; text-align:right">{{number_format($discountPriceAmount,2,'.',',')}}</div>
    </div>
   <br/>
<div style="clear:both"></div>
  <?php
    $sum = $item['quantity']+$sum;
  ?>
  @endforeach  
    <div style="clear:both"></div>
    <br/>
     <hr />
    <div style="padding:0px 20px; ">
      <div style="width:460px; float:left; font-size:14px; text-align:right;">&nbsp;</div>
      <div style="width:250px; float:left; text-align:right; font-size:14px;">Total Quantity</div>
      <div style="width:150px; float:left; text-align:right; font-size:14px;">{{$sum}}</div>
    </div>
    
    <div style="clear:both"></div>
    <div style="padding:0px 20px; ">
      <div style="width:460px; float:left; font-size:14px; text-align:right;">&nbsp;</div>
      <div style="width:250px; text-align:right; font-size:14px; float:left; font-weight:bold;">SubTotal</div>
      <div style="width:150px; text-align:right; font-size:14px; float:left;font-weight:bold;">{{Session::get('currency_symbol').number_format(($subTotalAmount),2,'.',',')}}</div>
    </div>
    @foreach($taxInfo as $rate=>$tax_amount)
    @if($rate != 0)
    <div style="clear:both"></div>
    <div style="padding:0px 20px; ">
      <div style="width:460px; float:left; font-size:14px; text-align:right;">&nbsp;</div>
      <div style="width:250px; text-align:right; font-size:14px; float:left;">Plus Tax({{$rate}}%)</div>
      <div style="width:150px; text-align:right; font-size:14px; float:left; ">{{Session::get('currency_symbol').number_format(($tax_amount),2,'.',',')}}</div>
    </div>
    <?php
      $taxAmount += $tax_amount;
    ?>    
    @endif
    @endforeach
    <br /><br />
    <div style="clear:both"></div>
    <hr />
    <div style="padding:0px 20px; ">
      <div style="width:460px; float:left; font-size:14px; text-align:right;">&nbsp;</div>
      <div style="width:250px; text-align:right; font-size:14px; float:left; font-weight:bold;">Grand Total</div>
      <div style="width:150px; text-align:right; font-size:14px; float:left; font-weight:bold;">{{Session::get('currency_symbol').number_format(($subTotalAmount+$taxAmount),2,'.',',')}}</div>
    </div>

  </div>
</body>
</html>