@extends('layouts.app')
@section('content')
  <section class="content">

      <!---Top Section Start-->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-10">
             <div class="top-bar-title">{{ trans('message.invoice.invoice') }}</div>
            </div>
            <div class="col-md-2">
              @if(!empty(Session::get('sales_add')))
                <a href="{{ url("sales/add") }}" class="btn btn-block btn-default btn-flat btn-border-orange">{{ trans('message.extra_text.new_sales_invoice') }}</a>
              @endif
            </div>
          </div>
        </div>
      </div>
      <!---Top Section End-->

    <div class="row">
      <div class="col-md-8 right-padding-col8">
          <div class="box box-default">
              <div class="box-body">
                  <div class="btn-group">
                  @if($saleDataInvoice->total > 0)
                  @if($saleDataInvoice->paid_amount == 0)
                    <div class="btn-unpaid" >{{ trans('message.invoice.unpaid')}}</div>
                  @elseif($saleDataInvoice->paid_amount > 0 && $saleDataInvoice->total > $saleDataInvoice->paid_amount )
                    <div class="btn-paid-partial" >{{ trans('message.invoice.partially_paid')}}</div>
                  @elseif($saleDataInvoice->total <= $saleDataInvoice->paid_amount)
                    <div class="btn-paid" >{{ trans('message.invoice.paid')}}</div>
                  @endif

                  @else
                  <div class="btn-paid" >{{ trans('message.invoice.paid')}}</div>
                  @endif
                  
                  </div>
                    <div class="btn-group pull-right">
                      <button title="Email" type="button" class="btn btn-default btn-flat" data-toggle="modal" data-target="#emailInvoice">{{ trans('message.extra_text.email') }}</button>
                      <a target="_blank" href="{{URL::to('/')}}/invoice/print/{{$saleDataOrder->order_no}}/{{$saleDataInvoice->order_no}}" title="Print" class="btn btn-default btn-flat">{{ trans('message.extra_text.print') }}</a>
                      <a target="_blank" href="{{URL::to('/')}}/invoice/pdf/{{$saleDataOrder->order_no}}/{{$saleDataInvoice->order_no}}" title="PDF" class="btn btn-default btn-flat">{{ trans('message.extra_text.pdf') }}</a>
                      
                      @if(!empty(Session::get('sales_edit')))
                      <a href="{{URL::to('/')}}/sales/edit/{{$saleDataInvoice->order_no}}" title="Edit" class="btn btn-default btn-flat">{{ trans('message.extra_text.edit') }}</a>
                      @endif

                      @if(!empty(Session::get('sales_delete')))
                      <a href="{{ URL::to('/')}}/invoice/delete-invoice/{{$saleDataInvoice->order_no}}" class="btn btn-default btn-flat delete-btn delete_data" type="button">
                            {{ trans('message.extra_text.delete') }}
                      </a>
                      @endif

                      @if($saleDataInvoice->total > $saleDataInvoice->paid_amount)
                      @if(!empty(Session::get('sales_add')))
                        <button title="{{ trans('message.invoice.pay_now')}}" type="button" class="btn btn-default btn-flat success-btn" data-toggle="modal" data-target="#payModal">{{ trans('message.invoice.pay_now')}}</button>
                      @endif
                       @endif
                      
                      @if(!empty(Session::get('shipment_add')))
                        @if($shipmentStatus == 'available')
                        <a href="{{URL::to('/')}}/shipment/add/{{$orderInfo->order_no}}" title="{{ trans('message.extra_text.manual_packing') }}" class="btn btn-default btn-flat success-btn">{{ trans('message.extra_text.pack') }}</a>
                        @endif
                      @endif
                      
                    </div>
              </div>

            <div class="box-body">
              <div class="row">
                
                  <div class="col-md-4">
                    <strong>{{ Session::get('company_name') }}</strong>
                    <h5 class="">{{ Session::get('company_street') }}</h5>
                    <h5 class="">{{ Session::get('company_city') }}, {{ Session::get('company_state') }}</h5>
                    <h5 class="">{{ Session::get('company_country_id') }}, {{ Session::get('company_zipCode') }}</h5>
                  </div>

                  <div class="col-md-4">
                  <strong>{{ trans('message.extra_text.bill_to') }}</strong>
                  <h5>{{ !empty($customerInfo->name) ? $customerInfo->name : ''}}</h5>
                  <h5>{{ !empty($customerInfo->billing_street) ? $customerInfo->billing_street : ''}} </h5>
                  <h5>{{ !empty($customerInfo->billing_state) ? $customerInfo->billing_state : ''}}{{ !empty($customerInfo->billing_city) ? ', '.$customerInfo->billing_city : ''}}</h5>
                  <h5>{{ !empty($customerInfo->billing_country_id) ? $customerInfo->billing_country_id : ''}} {{ !empty($customerInfo->billing_zip_code) ? ', '.$customerInfo->billing_zip_code : ''}}</h5>
                  </div>

                <div class="col-md-4">
                  <strong>{{ trans('message.table.invoice_no').' # '.$saleDataInvoice->reference }}</strong>
                  <h5>{{ trans('message.extra_text.location')}} : {{$saleDataInvoice->location_name}}</h5>
                  <h5>{{ trans('message.invoice.invoice_date')}} : {{formatDate($saleDataInvoice->ord_date)}}</h5>
                  <h5>{{ trans('message.invoice.due_date')}} : {{formatDate($due_date)}}</h5>
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
                        <th width="10%" class="text-center">{{ trans('message.table.rate') }}({{Session::get('currency_symbol')}})</th>
                        <th width="10%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                         <th class="text-center" width="10%">{{ trans('message.table.discount') }}(%)</th>
                        <th width="10%" class="text-center">{{ trans('message.table.amount') }}({{Session::get('currency_symbol')}})</th>
                      </tr>
                      <?php
                       $itemsInformation = '';
                       $taxAmount = 0;
                      ?>
                      @if(count($invoiceData)>0)
                       <?php $subTotal = 0;$units = 0;?>
                        @foreach($invoiceData as $result)
                          @if($result['quantity']>0)
                            <tr>
                              <td class="text-center">{{$result['description']}}</td>
                              <td class="text-center">{{$result['quantity']}}</td>
                              <td class="text-center">{{number_format($result['unit_price'],2,'.',',') }}</td>
                              <td class="text-center">{{number_format($result['tax_rate'],2,'.',',')}}</td>
                              <td class="text-center">{{number_format($result['discount_percent'],2,'.',',')}}</td>
                              <?php
                                $priceAmount = ($result['quantity']*$result['unit_price']);
                                $discount = ($priceAmount*$result['discount_percent'])/100;
                                $newPrice = ($priceAmount-$discount);
                                $subTotal += $newPrice;
                                $units += $result['quantity'];
                                $itemsInformation .= '<div>'.$result['quantity'].'x'.' '.$result['description'].'</div>';
                              ?>
                              <td align="right">{{number_format($newPrice,2,'.',',') }}</td>
                            </tr>
                            @endif
                        @endforeach
                        <tr class="tableInfos"><td colspan="5" align="right">{{ trans('message.table.total_qty') }}</td><td align="right" colspan="2">{{$units}}</td></tr>
                      <tr class="tableInfos"><td colspan="5" align="right">{{ trans('message.table.sub_total') }}</td><td align="right" colspan="2">{{ Session::get('currency_symbol').number_format($subTotal,2,'.',',') }}</td></tr>
                      @foreach($taxType as $rate=>$tax_amount)
                      <?php
                          $taxAmount += $tax_amount;
                        ?> 
                      @if($rate != 0)
                      <tr><td colspan="5" align="right">Plus Tax({{$rate}}%)</td><td colspan="2" class="text-right">{{ Session::get('currency_symbol').number_format($tax_amount,2,'.',',') }}</td></tr>
                      @endif
                      @endforeach
                      <tr class="tableInfos"><td colspan="5" align="right"><strong>{{ trans('message.table.grand_total') }}</strong></td><td colspan="2" class="text-right"><strong>{{ Session::get('currency_symbol').number_format($subTotal+$taxAmount,2,'.',',') }}</strong></td></tr>
                      <tr class="tableInfos"><td colspan="5" align="right">{{ trans('message.invoice.paid') }}</td><td colspan="2" class="text-right"> {{ Session::get('currency_symbol').number_format($saleDataInvoice->paid_amount,2,'.',',') }}</td></tr>
                      <tr class="tableInfos"><td colspan="5" align="right"><strong>{{ trans('message.invoice.due') }}</strong></td><td colspan="2" class="text-right"><strong>
                        @if(($subTotal+$taxAmount-$saleDataInvoice->paid_amount)<0)
                        -{{ Session::get('currency_symbol').number_format(abs($subTotal+$taxAmount-$saleDataInvoice->paid_amount),2,'.',',') }}
                        @else
                        {{ Session::get('currency_symbol').number_format(abs($subTotal+$taxAmount-$saleDataInvoice->paid_amount),2,'.',',') }}
                        @endif
                      </strong></td></tr>
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
      @include('layouts.includes.content_right_option')
      </div>
  </section>
  <!--Pay Modal Start-->
  <div class="modal fade" id="payModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{ trans('message.table.new_payment') }}</h4>
          <p>{{$saleDataInvoice->reference}}</p>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" id="payForm" action="{{url('payment/save')}}" method="POST">
          <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
          <input type="hidden" name="invoice_reference" value="{{$saleDataInvoice->reference}}">
          <input type="hidden" name="order_reference" value="{{$saleDataOrder->reference}}">
          <input type="hidden" name="customer_id" value="{{$customerInfo->debtor_no}}">

          <input type="hidden" name="order_no" value="{{$orderInfo->order_no}}">
          <input type="hidden" name="invoice_no" value="{{$invoice_no}}">
          
          <div class="form-group">
            <label for="payment_type_id" class="col-sm-3 control-label">{{ trans('message.form.payment_type') }} : </label>
            <div class="col-sm-6">

              <select style="width:100%" class="form-control" name="payment_type_id" id="payment_type_id">
                @foreach($payments as $payment)
                <option value="{{$payment->id}}">{{$payment->name}}</option>
                @endforeach
              </select>

            </div>
          </div>
          <div class="form-group">
            <label for="amount" class="col-sm-3 control-label">{{ trans('message.invoice.amount') }} : </label>
            <div class="col-sm-6">
              <input type="number" name="amount" value="{{$saleDataInvoice->total-$saleDataInvoice->paid_amount}}" class="form-control" id="amount" placeholder="Amount">
            </div>
          </div>
          <div class="form-group">
            <label for="payment_date" class="col-sm-3 control-label">{{ trans('message.invoice.paid_no') }} : </label>
            <div class="col-sm-6">
              <input type="text" name="payment_date" class="form-control" id="payment_date" placeholder="{{ trans('message.invoice.paid_on') }}">
            </div>
          </div>

          <div class="form-group">
            <label for="reference" class="col-sm-3 control-label">{{ trans('message.table.reference') }} : </label>
            <div class="col-sm-6">
              <input type="text" name="reference" class="form-control" id="reference" placeholder="{{ trans('message.table.reference') }}">
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
              <button type="submit" class="btn btn-primary btn-flat">{{ trans('message.invoice.pay_now') }}</button>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
  <!--Pay Modal End-->
  <!--Modal start-->
    <div id="emailInvoice" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <form id="sendVoiceInfo" method="POST" action="{{url('invoice/email-invoice-info')}}">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="{{$orderInfo->order_no}}" name="order_id" id="order_id">
        <input type="hidden" value="{{$invoice_no}}" name="invoice_id" id="invoice_id">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{ trans('message.email.email_invoice_info')}}</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="email">{{ trans('message.email.send_to')}}:</label>
              <input type="email" value="{{$customerInfo->email}}" class="form-control" name="email" id="email">
            </div>
            <?php
            $subjectInfo = str_replace('{order_reference_no}', $orderInfo->reference, $emailInfo->subject);
            $subjectInfo = str_replace('{invoice_reference_no}', $saleDataInvoice->reference, $subjectInfo);
            $subjectInfo = str_replace('{company_name}', Session::get('company_name'), $subjectInfo);
            ?>
            <div class="form-group">
              <label for="subject">{{ trans('message.email.subject')}}:</label>
              <input type="text" class="form-control" name="subject" id="subject" value="{{$subjectInfo}}">
            </div>
              <div class="form-groupa">
                  <?php
                  $bodyInfo = str_replace('{customer_name}', $customerInfo->name, $emailInfo->body);
                  $bodyInfo = str_replace('{order_reference_no}', $orderInfo->reference, $bodyInfo);
                  $bodyInfo = str_replace('{invoice_reference_no}',$saleDataInvoice->reference, $bodyInfo);
                  $bodyInfo = str_replace('{due_date}',$due_date, $bodyInfo);
                  $bodyInfo = str_replace('{billing_street}', $customerInfo->billing_street, $bodyInfo);
                  $bodyInfo = str_replace('{billing_city}', $customerInfo->billing_city, $bodyInfo);
                  $bodyInfo = str_replace('{billing_state}', $customerInfo->billing_state, $bodyInfo);
                  $bodyInfo = str_replace('{billing_zip_code}', $customerInfo->billing_zip_code, $bodyInfo);
                  $bodyInfo = str_replace('{billing_country}', $customerInfo->billing_country_id, $bodyInfo);                      
                  $bodyInfo = str_replace('{company_name}', Session::get('company_name'), $bodyInfo);
                  $bodyInfo = str_replace('{invoice_summery}', $itemsInformation, $bodyInfo);                     
                  $bodyInfo = str_replace('{currency}', Session::get('currency_symbol'), $bodyInfo);
                  $bodyInfo = str_replace('{total_amount}', $saleDataInvoice->total, $bodyInfo); 
                  ?>
                  <textarea id="compose-textarea" name="message" id='message' class="form-control editor" style="height: 200px">{{$bodyInfo}}</textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">{{ trans('message.email.close')}}</button><button type="submit" class="btn btn-primary btn-sm">{{ trans('message.email.send')}}</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  <!--Modal end -->
@include('layouts.includes.message_boxes') 
@endsection
@section('js')
<script type="text/javascript">
$(document).ready(function() {
      $("#payment_type_id").select2();
      $('#payment_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: '{{Session::get('date_format_type')}}'
    });  

    $('#payment_date').datepicker('update', new Date());

// Item form validation
    $('#payForm').validate({
        rules: {
            payment_type_id: {
                required: true
            },
            amount: {
                required: true
            },
            payment_date:{
               required: true
            }                   
        }
    });

      $(function () {
        $(".editor").wysihtml5();
      });

    $('#sendVoiceInfo').validate({
        rules: {
            email: {
                required: true
            },
            subject:{
               required: true,
            },
            message:{
               required: true,
            }                   
        }
    }); 


$('.delete_data').bootstrap_confirm_delete({
  heading: "{{ trans('message.invoice.delete_invoice') }}",
  message: "{{ trans('message.invoice.delete_invoice_confirm') }}",
  data_type: null,
});       

});

</script>
@endsection