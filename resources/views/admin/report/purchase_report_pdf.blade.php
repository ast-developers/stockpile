<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Purchase report</title>
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

  <div style="width:100%; margin:0px auto;">
  <div style="height:130px">
    <div style="width:70%; float:left; font-size:15px; color:#383838; font-weight:400;">
      <div style="font-size:20px;"><strong>Purchase Report</strong></div>
      <div>Print Date : {{formatDate(date('d-m-Y'))}}</div>
      
       @if($type == 'custom')
       <div>Date : {{ $date_range }}</div>
       @endif

       <div>Product : {{isset($product) && ($product == 'all') ? 'All' : getItemName($product)}}</div>
       <div>Supplier : {{isset($supplier) && ($supplier == 'all') ? 'All' : getSupplier($supplier)}}</div>
       <div>Location : {{isset($location) && ($location == 'all') ? 'All' : getDestinatin($location)}}</div>

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
   <td>
     <?php
     if($type == 'custom' || $type == 'daily'){
      echo "Date";
    }else if($type == 'monthly'){
      echo 'Month';
    }elseif( $type == 'yearly'){
      if($year=='all'){
        echo "Year";
      }else if($year !='all' && $month == 'all'){
        echo 'Month';
      }else if($year !='all' && $month != 'all'){
        echo "Date";
      }
    }
     ?>

   </td>
   <td>No Of Order</td>
   <td>Purchase Volume</td>
   <td>Costs Value({{Session::get('currency_symbol')}})</td>
 </tr>
<?php
  $qty = 0;
  $cost = 0;
  $order = 0;
?>
  @foreach ($list as $item)
<?php
  $qty += $item->qty;
  $cost += $item->cost;
  $order += $item->order_total;
?>
  <tr style="background-color:#fff; text-align:center; font-size:13px; font-weight:normal;">  
  <td>

     <?php
     if($type == 'custom' || $type == 'daily'){
      echo date('d-m-Y',strtotime($item->ord_date));
    }else if($type == 'monthly'){
      echo date('F-Y',strtotime($item->ord_date));
    }elseif( $type == 'yearly'){

      if($year=='all'){
       echo date('Y',strtotime($item->ord_date));
      }else if($year !='all' && $month == 'all'){
        echo date('F-Y',strtotime($item->ord_date));
      }else if($year !='all' && $month != 'all'){
        echo date('d-m-Y',strtotime($item->ord_date));
      }

    }
     ?>

  </td>
  <td>{{ $item->order_total }}</td>
  <td>{{ $item->qty }}</td>
  <td>{{ number_format(($item->cost),2,'.',',') }}</td>
  </tr>
  @endforeach  
  <tr style="background-color:#f0f0f0; text-align:right; font-size:13px; font-weight:normal;">
    <td colspan="1"><strong>Total</stong></td>
    <td align="center"><strong>{{$order}}</stong></td>
    <td align="center"><strong>{{$qty}}</stong></td>
    <td align="center"><strong>{{number_format(abs($cost),2,'.',',') }}</stong></td> 
  </tr>
  </div>
  </div>
</body>
</html>