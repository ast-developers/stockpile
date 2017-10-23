<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Purchase Invoice</title>
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
   <div style="font-size:30px; font-weight:bold; color:#383838;">Purchase Order</div>
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
      <div><strong>{{!empty($purchData->supp_name) ? $purchData->supp_name : ''}}<strong></div>
      <div>{{!empty($purchData->address) ? $purchData->address : ''}}</div>
      <div>{{!empty($purchData->city) ? $purchData->city : ''}}{{!empty($purchData->state) ? ', '.$purchData->state : ''}}</div>
      <div>{{!empty($purchData->country) ? $purchData->country : ''}}{{!empty($purchData->zipcode) ? ', '.$purchData->zipcode : ''}}</div>
    </div>
    <div style="width:200px; float:left; text-align:right; font-size:15px; color:#383838; font-weight:400;">
      <div><strong>Invoice No # {{$purchData->reference}}</strong></div>
      <div>Date : {{formatDate($purchData->ord_date)}}</div>
    </div>
  </div>

  <div style="clear:both"></div>
  <div style="margin-top:30px;">
   <table style="width:100%; border-radius:2px; border:2px solid #d1d1d1; border-collapse: collapse;">
      <tr style="background-color:#f0f0f0; border-bottom:1px solid #d1d1d1; text-align:center; font-size:13px; font-weight:bold;">
      <td>S/N</td>
      <td>Description</td>
      <td>Quantity</td>
      <td>Price({{ Session::get('currency_symbol')}})</td>
      <td>Tax(%)</td>
      <td style="padding-right:10px;text-align:right">Amount({{ Session::get('currency_symbol')}})</td>
    </tr>

  <?php
    $taxAmount      = 0;
    $subTotal = 0;
    $qtyTotal       = 0;
    $priceAmount    = 0;
    $sum = 0;
    $i=0;
    $units = 0;
  ?>
  @foreach ($invoiceItems as $item)
   <?php
        $priceAmount = ($item->quantity_received*$item->unit_price);
        $subTotal += $priceAmount;
        $units += $item->quantity_received;
   ?>
      
    <tr style="background-color:#fff; text-align:center; font-size:13px; font-weight:normal;">
      <td>{{++$i}}</td>
      <td>{{$item->description}}</td>
      <td>{{$item->quantity_received}}</td>
      <td>{{number_format(($item->unit_price),2,'.',',')}}</td>
      <td>{{number_format($item->tax_rate,2,'.',',')}}</td>
      <td style="padding-right:10px;text-align:right">{{number_format($priceAmount,2,'.',',')}}</td>
    </tr>
  <?php
    $sum = ($item->quantity_received+$sum);
  ?>
  @endforeach   

    <tr style="background-color:#fff; text-align:right; font-size:13px; font-weight:normal; height:100px;">
      <td colspan="5" style="border-bottom:none">
         Total Quantity<br />
       <strong>SubTotal</strong><br/>
        </td>   
      <td style="text-align:right; padding-right:10px;border-bottom:none">
        {{$sum}}<br />
       {{Session::get('currency_symbol').number_format(($subTotal),2,'.',',')}}<br/>
      </td>
    </tr>

    @foreach($taxType as $rate=>$tax_amount)
    @if($rate != 0)

    <?php
      $taxAmount += $tax_amount;
    ?>  
    <tr style="background-color:#fff; text-align:right; font-size:13px; font-weight:normal; height:100px;">
      <td colspan="5" style="border-bottom:none">
         Plus Tax({{$rate}}%)
        </td>
      <td style="text-align:right; padding-right:10px; border-bottom:none">
       {{$taxAmount}}
      </td>
    </tr>     
    @endif 
    @endforeach 
    <tr style="background-color:#f0f0f0; text-align:right; font-size:13px; font-weight:normal;">
      <td colspan="5" style="text-align:right;"><strong>Grand Total</strong></td>
      <td style="text-align:right; padding-right:10px"><strong>{{Session::get('currency_symbol').number_format(($subTotal+$taxAmount),2,'.',',')}}</strong></td>
    </tr>
   </table> 
    </div>   
  </div>
  <script type="text/javascript">
      window.onload = function() { window.print(); }
 </script>

</body>
</html>
