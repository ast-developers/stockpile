<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Shipment</title>
</head>
<style>
 body{ font-family:Helvetica, sans-serif; color:#121212; line-height:22px;}
 table, tr, td{
    border-bottom: 1px solid #d1d1d1;
    padding: 6px 0px;
}
tr{ height:40px;}
</style>
<body>
  <div style="width:900px; margin:15px auto;">
    <div style="width:450px; float:left; margin-top:20px;height:50px;">
   <div style="font-size:30px; font-weight:bold; color:#383838;">Shipment</div>
  </div>
  <div style="width:450px; float:right;height:50px;">
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
    </div>
    <div style="width:300px; float:left;font-size:15px; color:#383838; font-weight:400;">
      <div><strong>Ship To</strong></div>
      <div>{{ !empty($customerInfo->br_name) ? $customerInfo->br_name : ''}}</div>
      <div>{{ !empty($customerInfo->shipping_street) ? $customerInfo->shipping_street :'' }}</div>
      <div>{{ !empty($customerInfo->shipping_city) ? $customerInfo->shipping_city : ''}}{{ !empty($customerInfo->shipping_state) ? ', '.$customerInfo->shipping_state : ''}}</div>
      <div>{{ !empty($customerInfo->shipping_country_id) ? $customerInfo->shipping_country_id :''}}{{ !empty($customerInfo->shipping_zip_code) ? ', '.$customerInfo->shipping_zip_code : ''}}</div>
    </div>
    <div style="width:200px; float:left; text-align:right; font-size:15px; color:#383838; font-weight:400;">
      <div><strong>Shipment No # {{ sprintf("%04d", $shipment->id)}}</strong></div>
      @if($shipment->status == 1)
      <div>Status : Delivered</div>
      <div>Date : {{formatDate($shipment->delivery_date)}}</div>   
      @else
      <div>Status : Packed</div>
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
      <td>Price($)</td>
      <td>Tax(%)</td>
      <td>Discount(%)</td>
      <td style="padding-right:10px;text-align:right">Amount($)</td>
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
  @foreach ($shipmentItem as $item)
   <?php
    $price = ($item->quantity*$item->unit_price);
    $discount =  ($item->discount_percent*$price)/100;
    $discountPriceAmount = ($price-$discount);
    $qtyTotal +=$item->quantity; 
    $subTotalAmount += $discountPriceAmount; 
   ?> 
   @if($item->quantity>0) 
      
    <tr style="background-color:#fff; text-align:center; font-size:13px; font-weight:normal;">
      <td>{{++$i}}</td>
      <td>{{$item->description}}</td>
      <td>{{$item->quantity}}</td>
      <td>{{number_format(($item->unit_price),2,'.',',')}}</td>
      <td>{{number_format($item->tax_rate,2,'.',',')}}</td>
      <td>{{number_format($item->discount_percent,2,'.',',')}}</td>
      <td style="padding-right:10px;text-align:right">{{number_format($discountPriceAmount,2,'.',',')}}</td>
    </tr>
   @endif
  <?php
    $sum = $item->quantity+$sum;
  ?>
  @endforeach  

    <tr style="background-color:#fff; text-align:right; font-size:13px; font-weight:normal; height:100px;">
      <td colspan="6" style="border-bottom:none">
         Total Quantity<br />
       <strong>SubTotal</strong><br/>
        </td>   

      <td style="text-align:right; padding-right:10px;border-bottom:none">
        {{$sum}}<br />
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
      <td colspan="6" style="text-align:right;"><strong>Grand Total</strong></td>
      <td style="text-align:right; padding-right:10px"><strong>{{Session::get('currency_symbol').number_format(($subTotalAmount+$taxAmount),2,'.',',')}}</strong></td>
    </tr>
   </table> 
    </div>
    
    
  </div>
</body>
</html>
