@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
        <!-- /.box-header -->
          <div class="box-body">
                <div class="row">
                  <div class="col-md-4">
                    <strong>{{ $settings[8]->value }}</strong>
                    <h5>{{ $settings[11]->value }}</h5>
                    <h5>{{ $settings[12]->value }}, {{ $settings[13]->value }}</h5>
                    <h5>{{ $settings[15]->value }}, {{ $settings[14]->value }}</h5>
                  </div>
                  
                  <div class="col-md-4">
                  <strong>{{ trans('message.extra_text.shiptment_to') }}</strong>
                  <h5>{{ !empty($customerInfo->br_name) ? $customerInfo->br_name : ''}}</h5>
                  <h5>{{ !empty($customerInfo->shipping_street) ? $customerInfo->shipping_street :'' }}</h5>
                  <h5>{{ !empty($customerInfo->shipping_city) ? $customerInfo->shipping_city : ''}} {{ !empty($customerInfo->shipping_state) ? ', '.$customerInfo->shipping_state : ''}}</h5>
                  <h5>{{ !empty($customerInfo->shipping_country_id) ? $customerInfo->shipping_country_id :''}} {{ !empty($customerInfo->shipping_zip_code) ? ', '.$customerInfo->shipping_zip_code : ''}}</h5>
                  </div>


                  <div class="col-md-4">
                  <strong>{{ trans('message.invoice.shift_no').' # '.sprintf("%04d", $shipment->id)}}</strong>
                   <?php
                      if($shipment->status == 0){
                        $status = trans('message.invoice.shipment_packed');
                      }else{
                        $status = trans('message.invoice.shipment_delivered');
                      }
                   ?>
                   <h5 class="text-primary"><strong>{{ trans('message.invoice.status').' # '.$status}}</strong></h5>
                   @if($shipment->status == 1)
                   <h5>{{ trans('message.invoice.deliver_date')}} : {{formatDate($shipment->delivery_date)}}</h5>
                    @endif
                  </div>

                </div>
          </div>

          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                  <div class="table-responsive">
                      <table class="table table-bordered">
                        <tbody>
                        <tr class="tbl_header_color">
                          <th width="5%" class="text-left">{{ trans('message.invoice.shipment_no') }}</th>
                          <th width="30%" class="text-left">{{ trans('message.table.description') }}</th>
                          <th width="10%" class="text-center">{{ trans('message.table.quantity') }}</th>
                          <th width="10%" class="text-center">{{ trans('message.table.rate') }}({{ $currency->symbol }})</th>
                          <th width="10%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                          <th class="text-center" width="10%">{{ trans('message.table.discount') }}(%)</th>
                          <th width="15%" class="text-center">{{ trans('message.table.amount') }}({{ $currency->symbol }})</th>
                        </tr>
                        <?php
                          $taxAmount      = 0;
                          $subTotalAmount = 0;
                          $qtyTotal       = 0;
                          $priceAmount    = 0;
                          $discount       = 0;
                          $discountPriceAmount = 0;
                          $itemsInformation = '';
                        ?>
                          @foreach($shipmentItem as $k=>$result)
                             <?php
                              $price = ($result->quantity*$result->unit_price);
                              $discount =  ($result->discount_percent*$price)/100;
                              $discountPriceAmount = ($price-$discount);
                              $qtyTotal +=$result->quantity; 
                              $subTotalAmount += $discountPriceAmount; 
                             // Create item information for email template
                              $itemsInformation .= '<div>'.$result->quantity.'x'.' '.$result->description.'</div>';
                             ?> 
                             @if($result->quantity>0)
                            <tr>
                              <td class="text-left">{{++$k}}</td>
                              <td class="text-left">{{$result->description}}</td>
                              <td class="text-center">{{$result->quantity}}</td>
                              <td class="text-center">{{number_format($result->unit_price,2,'.',',') }}</td>
                              <td class="text-center">{{number_format($result->tax_rate,2,'.',',') }}</td>
                              <td class="text-center">{{number_format($result->discount_percent,2,'.',',') }}</td>
                              <td class="text-right">{{number_format($discountPriceAmount,2,'.',',') }}</td>
                            </tr>
                            @endif
                            @endforeach
                            <tr><td colspan="6" align="right">{{ trans('message.table.total_quantity') }}</td><td align="right">{{$qtyTotal}}</td></tr>
                            <tr><td colspan="6" align="right"><strong>{{ trans('message.table.sub_total') }}</strong></td><td align="right"><strong>{{ $currency->symbol.number_format($subTotalAmount,2,'.',',') }}</strong></td></tr>
                            
                            @foreach($taxInfo as $rate=>$tax_amount)
                            @if($rate != 0)
                            <tr>
                              <td colspan="6" align="right">{{ trans('message.invoice.plus_tax') }}({{$rate}}%)</td>
                              <td colspan="2" align="right">{{ $currency->symbol.number_format($tax_amount,2,'.',',') }}</td></tr>
                            <?php
                              $taxAmount += $tax_amount;
                            ?>
                            @endif
                            @endforeach
                            <tr class="tableInfos">
                              <td colspan="6" align="right"><strong>{{ trans('message.table.grand_total') }}</strong></td>
                              <td align="right" colspan="2"><strong>{{ $currency->symbol.number_format(($subTotalAmount+$taxAmount),2,'.',',') }}</strong></td>
                            </tr>
                         
                        </tbody>
                      </table>
                  </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    </section>
  
@endsection
@section('js')
<script type="text/javascript">
  
</script>
@endsection