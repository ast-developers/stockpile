@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
             <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.shipment') }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Default box -->
    <div class="row">
      <div class="col-md-8 right-padding-col8">
        <div class="box box-default">
          <div class="box-body">
                <div class="btn-group pull-right">
                  <button title="Email" type="button" class="btn btn-default btn-flat" data-toggle="modal" data-target="#emailShipment">{{ trans('message.extra_text.email') }}</button>
                  <a target="_blank" href="{{URL::to('/')}}/shipment/print/{{$orderInfo->order_no}}/{{$shipment->id}}" title="Print" class="btn btn-default btn-flat">{{ trans('message.extra_text.print') }}</a>
                  <a target="_blank" href="{{URL::to('/')}}/shipment/pdf/{{$orderInfo->order_no}}/{{$shipment->id}}" title="PDF" class="btn btn-default btn-flat">{{ trans('message.extra_text.pdf') }}</a>
                  
                   @if(!empty(Session::get('shipment_edit')))
                    <a href="{{URL::to('/')}}/shipment/edit/{{$shipment->id}}" title="Edit" class="btn btn-default btn-flat">{{ trans('message.extra_text.edit') }}</a>
                   @endif
                  @if($shipment->status == 0)
                  @if(!empty(Session::get('shipment_edit')))
                  <a href="{{URL::to('/')}}/shipment/delivery/{{$orderInfo->order_no}}/{{$shipment->id}}" title="Deliver" class="btn btn-default btn-flat success-btn">{{ trans('message.extra_text.deliver') }}</a>
                  @endif
                  @endif
                 @if(!empty(Session::get('shipment_delete')))
                  <form method="POST" action="{{ url("shipment/delete/$shipment->id") }}" accept-charset="UTF-8" style="display:inline">
                      {!! csrf_field() !!}
                      <button class="btn btn-default btn-flat delete-btn" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_shipment') }}" data-message="{{ trans('message.invoice.delete_shipment_confirm') }}">
                         {{ trans('message.extra_text.delete') }}
                      </button>
                  </form>
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
                  <strong>{{ trans('message.extra_text.shiptment_to') }}</strong>
                  <h5>{{ !empty($customerInfo->br_name) ? $customerInfo->br_name : ''}}</h5>
                  <h5>{{ !empty($customerInfo->shipping_street) ? $customerInfo->shipping_street :'' }}</h5>
                  <h5>{{ !empty($customerInfo->shipping_city) ? $customerInfo->shipping_city : ''}}{{ !empty($customerInfo->shipping_state) ? ', '.$customerInfo->shipping_state : ''}}</h5>
                  <h5>{{ !empty($customerInfo->shipping_country_id) ? $customerInfo->shipping_country_id :''}}{{ !empty($customerInfo->shipping_zip_code) ? ', '.$customerInfo->shipping_zip_code : ''}}</h5>
                  </div>
                
                <div class="col-md-4">
                    <strong class="">{{ trans('message.invoice.shift_no').' # '.sprintf("%04d", $shipment->id)}}</strong>
                   <h5>{{ trans('message.extra_text.location')}} : {{$orderInfo->location_name}}</h5>
                   <?php
                      if($shipment->status == 0){
                        $status = trans('message.invoice.shipment_packed');
                      }else{
                        $status = trans('message.invoice.shipment_delivered');
                      }
                   ?>
                   <h5>{{ trans('message.invoice.status')}} : {{$status}} </h5>
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
                          <th width="10%" class="text-center">{{ trans('message.table.rate') }}({{ Session::get('currency_symbol')}})</th>
                          <th width="10%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                          <th class="text-center" width="10%">{{ trans('message.table.discount') }}(%)</th>
                          <th width="15%" class="text-center">{{ trans('message.table.amount') }}({{ Session::get('currency_symbol')}})</th>
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
                            <tr><td colspan="6" align="right"><strong>{{ trans('message.table.sub_total') }}</strong></td><td align="right"><strong>{{ Session::get('currency_symbol').number_format($subTotalAmount,2,'.',',') }}</strong></td></tr>
                            
                            @foreach($taxInfo as $rate=>$tax_amount)
                            @if($rate != 0)
                            <tr>
                              <td colspan="6" align="right">{{ trans('message.invoice.plus_tax') }}({{$rate}}%)</td>
                              <td colspan="2" align="right">{{ Session::get('currency_symbol').number_format($tax_amount,2,'.',',') }}</td></tr>
                            <?php
                              $taxAmount += $tax_amount;
                            ?>
                            @endif
                            @endforeach
                            <tr class="tableInfos">
                              <td colspan="6" align="right"><strong>{{ trans('message.table.grand_total') }}</strong></td>
                              <td align="right" colspan="2"><strong>{{ Session::get('currency_symbol').number_format(($subTotalAmount+$taxAmount),2,'.',',') }}</strong></td>
                            </tr>
                         
                        </tbody>
                      </table>
                  </div>
              </div>
            </div>
          </div>

        </div>
      </div>
        <!--Modal start-->
        <div id="emailShipment" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <form id="sendShipmentInfo" method="POST" action="{{url('shipment/email-shipment-info')}}">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden" value="{{$orderInfo->order_no}}" name="order_id" id="order_id">
            <input type="hidden" value="{{$shipment->id}}" name="shipment_id" id="shipment_id">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ trans('message.email.email_shipment_info')}}</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="email">{{ trans('message.email.send_to')}}:</label>
                  <input type="email" value="{{$customerInfo->email}}" class="form-control" name="email" id="email">
                </div>
                <?php
                $subjectInfo = str_replace('{order_reference_no}', $orderInfo->reference, $emailInfo->subject);
                $subjectInfo = str_replace('{company_name}', Session::get('company_name'), $subjectInfo);
                ?>
                <div class="form-group">
                  <label for="subject">{{ trans('message.email.subject')}}:</label>
                  <input type="text" class="form-control" name="subject" id="subject" value="{{$subjectInfo}}">
                </div>
                  <div class="form-groupa">
                      <?php
                      $packed_on = date('Y-m-d',strtotime($shipment->packed_date));
                      if(!empty($shipment->delivery_date)){
                      $delivery_on = date('Y-m-d',strtotime($shipment->delivery_date));
                      $delivery_on = formatDate($delivery_on);
                      }else{
                        $delivery_on = '';
                      }
                      //d($packed_on,1);
                      $bodyInfo = str_replace('{customer_name}', $customerInfo->name, $emailInfo->body);
                      $bodyInfo = str_replace('{order_reference_no}', $orderInfo->reference, $bodyInfo);
                      $bodyInfo = str_replace('{packed_date}',formatDate($packed_on), $bodyInfo);
                      $bodyInfo = str_replace('{delivery_date}',$delivery_on, $bodyInfo);
                      $bodyInfo = str_replace('{shipping_street}', $customerInfo->shipping_street, $bodyInfo);
                      $bodyInfo = str_replace('{shipping_city}', $customerInfo->shipping_city, $bodyInfo);
                      $bodyInfo = str_replace('{shipping_state}', $customerInfo->shipping_state, $bodyInfo);
                      $bodyInfo = str_replace('{shipping_zip_code}', $customerInfo->shipping_zip_code, $bodyInfo);
                      $bodyInfo = str_replace('{shipping_country}', $customerInfo->shipping_country_id, $bodyInfo);                      
                      $bodyInfo = str_replace('{company_name}', Session::get('company_name'), $bodyInfo);
                      $bodyInfo = str_replace('{item_information}', $itemsInformation, $bodyInfo);                     
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

      @include('layouts.includes.content_right_option')
    </div>
    </section>
@include('layouts.includes.message_boxes')    
@endsection
@section('js')
    <script type="text/javascript">
      $(function () {
        $(".editor").wysihtml5();
      });

    $('#sendShipmentInfo').validate({
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

    </script>
@endsection