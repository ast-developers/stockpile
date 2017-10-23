@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                   <strong> Order No # {{$orderInfo->reference}}</strong><br>
                   Date : {{formatDate($orderInfo->ord_date)}}
                 </div>
                 <div class="col-md-6 text-right">
                  <div class="btn-group pull-right">
                   <a target="_blank" href="{{URL::to('/')}}/customer-panel/orderPdf/{{$orderInfo->order_no}}" title="PDF" class="btn btn-default btn-flat">PDF</a>
                   <a target="_blank" href="{{URL::to('/')}}/customer-panel/orderPrint/{{$orderInfo->order_no}}" title="Print" class="btn btn-default btn-flat">Print</a>
                  </div>
                 </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-4">
                    <strong>{{ $settings[8]->value }}</strong>
                    <h5>{{ $settings[11]->value }}</h5>
                    <h5>{{ $settings[12]->value }}, {{ $settings[13]->value }}</h5>
                    <h5>{{ $settings[15]->value }}, {{ $settings[14]->value }}</h5>
                  </div>
                  
                  <div class="col-md-4">
                  <strong>{{ trans('message.extra_text.bill_to') }}</strong>
                  <h5>{{ !empty($customerInfo->name) ? $customerInfo->name : ''}}</h5>
                  <h5>{{ !empty($customerInfo->billing_street) ? $customerInfo->billing_street : ''}} </h5>
                  <h5>{{ !empty($customerInfo->billing_state) ? $customerInfo->billing_state : ''}}{{ !empty($customerInfo->billing_city) ? ', '.$customerInfo->billing_city : ''}}</h5>
                  <h5>{{ !empty($customerInfo->billing_country_id) ? $customerInfo->billing_country_id : ''}} {{ !empty($customerInfo->billing_zip_code) ? ', '.$customerInfo->billing_zip_code : ''}}</h5>
                  </div>
                  
                  <div class="col-md-4">
                  <strong>{{ trans('message.extra_text.shiptment_to') }}</strong>
                  <h5>{{ !empty($customerInfo->br_name) ? $customerInfo->br_name : ''}}</h5>
                  <h5>{{ !empty($customerInfo->shipping_street) ? $customerInfo->shipping_street :'' }}</h5>
                  <h5>{{ !empty($customerInfo->shipping_city) ? $customerInfo->shipping_city : ''}} {{ !empty($customerInfo->shipping_state) ? ', '.$customerInfo->shipping_state : ''}}</h5>
                  <h5>{{ !empty($customerInfo->shipping_country_id) ? $customerInfo->shipping_country_id :''}} {{ !empty($customerInfo->shipping_zip_code) ? ', '.$customerInfo->shipping_zip_code : ''}}</h5>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="box-body no-padding">
                      <div class="table-responsive">
                      <table class="table table-bordered" id="salesInvoice">
                        <tbody>
                        <tr class="tbl_header_color dynamicRows">
                          <th width="30%" class="text-center">{{ trans('message.table.description') }}</th>
                          <th width="10%" class="text-center">{{ trans('message.table.quantity') }}</th>
                          <th width="10%" class="text-center">{{ trans('message.table.rate') }}({{$currency->symbol}})</th>
                          <th width="10%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                           <th class="text-center" width="10%">{{ trans('message.table.discount') }}(%)</th>
                          <th width="10%" class="text-center">{{ trans('message.table.amount')}}({{$currency->symbol}})</th>
                        </tr>
                        @if(count($invoiceData)>0)
                         <?php $subTotal = 0;$units = 0;$itemsInformation = '';?>
                          @foreach($invoiceData as $result)
                              <tr>
                                <td class="text-center">{{$result['description']}}</td>
                                <td class="text-center">{{$result['quantity']}}</td>
                                <td class="text-center">{{ number_format($result['unit_price'],2,'.',',') }}</td>
                                <td class="text-center">{{ number_format($result['tax_rate'],2,'.',',') }}</td>
                                <td class="text-center">{{ number_format($result['discount_percent'],2,'.',',') }}</td>
                                <?php
                                  $priceAmount = ($result['quantity']*$result['unit_price']);
                                  $discount = ($priceAmount*$result['discount_percent'])/100;
                                  $newPrice = ($priceAmount-$discount);
                                  $subTotal += $newPrice;
                                  $units += $result['quantity'];
                                  $itemsInformation .= '<div>'.$result['quantity'].'x'.' '.$result['description'].'</div>';
                                ?>
                                <td align="right">{{ number_format($newPrice,2,'.',',') }}</td>
                              </tr>
                          @endforeach
                          <tr class="tableInfos"><td colspan="5" align="right">{{ trans('message.table.total_qty') }}</td><td align="right" colspan="2">{{$units}}</td></tr>
                        <tr class="tableInfos"><td colspan="5" align="right">{{ trans('message.table.sub_total') }}</td><td align="right" colspan="2">{{ $currency->symbol.number_format($subTotal,2,'.',',') }}</td></tr>
                        @foreach($taxType as $rate=>$tax_amount)
                        @if($rate != 0)
                        <tr><td colspan="5" align="right">Plus Tax({{$rate}}%)</td><td colspan="2" class="text-right">{{ $currency->symbol.number_format($tax_amount,2,'.',',') }}</td></tr>
                        @endif
                        @endforeach
                          <tr class="tableInfos"><td colspan="5" align="right"><strong>{{ trans('message.table.grand_total') }}</strong></td><td colspan="2" class="text-right"><strong>{{Session::get('currency_symbol').number_format($saleData->total,2,'.',',')}}</strong></td></tr>
                          <?php
                           $invoiceAmount = 0;
                            if(!empty($paymentsList)){
                             
                              foreach ($paymentsList as $key => $paymentAmount) {
                               $invoiceAmount += $paymentAmount->amount;
                              }
                            }
                          ?>
                          <tr><td colspan="5" align="right">{{ trans('message.invoice.paid') }}</td><td colspan="2" class="text-right">{{$currency->symbol.number_format($invoiceAmount,2,'.',',')}}</td></tr>
                          <tr class="tableInfos"><td colspan="5" align="right"><strong>{{ trans('message.invoice.due') }}</strong></td><td colspan="2" class="text-right"><strong>{{$currency->symbol.number_format(($saleData->total-$invoiceAmount),2,'.',',')}}</strong></td></tr>
                        @endif
                        </tbody>
                      </table>
                      </div>
                      <br><br>
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