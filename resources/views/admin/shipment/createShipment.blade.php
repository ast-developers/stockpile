@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body">
          @if($shipmentStatus =='available')
        <form action="{{url('shipment/store')}}" method="POST" id="shipmentForm">  
          
          <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
          <input type="hidden" value="{{$orderInfo->order_no}}" name="order_no" id="order_no">
          <input type="hidden" value="{{$orderInfo->reference}}" name="reference" id="reference">        

        <div class="row">
          <div class="col-md-3">
            <strong>{{ Session::get('company_name') }}</strong>
            <h5 class="">{{ Session::get('company_street') }}</h5>
            <h5 class="">{{ Session::get('company_city') }}, {{ Session::get('company_state') }}</h5>
            <h5 class="">{{ Session::get('company_country_id') }}, {{ Session::get('company_zipCode') }}</h5>
          </div>

          <div class="col-md-3">
            
            <strong>{{ trans('message.extra_text.shiptment_to') }}</strong>
            <h5>{{ !empty($orderInfo->br_name) ? $orderInfo->br_name : ''}}</h5>
            <h5>{{ !empty($orderInfo->shipping_street) ? $orderInfo->shipping_street :'' }}</h5>
            <h5>{{ !empty($orderInfo->shipping_city) ? $orderInfo->shipping_city : ''}}{{ !empty($orderInfo->shipping_state) ? ', '.$orderInfo->shipping_state : ''}}</h5>
            <h5>{{ !empty($orderInfo->shipping_country_id) ? $orderInfo->shipping_country_id :''}}{{ !empty($orderInfo->shipping_zip_code) ? ', '.$orderInfo->shipping_zip_code : ''}}</h5>
          </div>

          <div class="col-md-3">
            <strong>{{ trans('message.invoice.order_no') }} # {{$orderInfo->reference}}</strong>
                             <h5>{{ trans('message.extra_text.location')}} : {{ getDestinatin($orderInfo->from_stk_loc) }}</h5>
          </div>
            <div class="col-md-3">
              <div class="form-group">
                <label >{{ trans('message.invoice.packed_date') }}<span class="text-danger"> *</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control" id="packed_date" type="text" name="packed_date">
                </div>
              </div>
            </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <div class="text-center" id="quantityMessage" style="color:red; font-weight:bold">
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <div class="table-responsive">
                <table class="table table-bordered" id="salesInvoice">
                  <tbody>
                  <tr class="tbl_header_color dynamicRows">
                    <th width="10%" class="text-center">{{ trans('message.table.item_id') }}</th>
                    <th width="30%" class="text-center">{{ trans('message.table.description') }}</th>
                    <th width="10%" class="text-center">{{ trans('message.table.quantity') }}</th>
                    <th width="10%" class="text-center">{{ trans('message.table.rate') }}</th>
                    <th width="10%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                     <th class="text-center" width="10%">{{ trans('message.table.discount') }}(%)</th>
                    <th width="10%" class="text-center">{{ trans('message.table.amount') }}</th>
                  </tr>
                  <?php
                  $taxArray = array();
                  $subTotalAmount = 0;
                  ?>
                  @if(count($shipmentItem)>0)
                    @foreach($shipmentItem as $k=>$result)
                        <?php

                          if((int)abs($result->item_invoiced)>0){
                            if($result->shipment_qty>0){
                              $unShiptedQty = ((int)abs($result->item_invoiced)-$result->shipment_qty);
                            }else{
                            $unShiptedQty = (int)abs($result->item_invoiced);
                          }
                          }
                          else{
                            $unShiptedQty = $result->quantity;
                          }
                        ?>
                        @if( $unShiptedQty >0 )
                          @if(in_array($result->stock_id,$invoicedItems))
                          <?php 
                            $taxArray[$k] = $result->tax_type_id;;
                          ?>
                            <tr class="nr" data-tax_type="{{$result->tax_rate}}" id="rowid{{$result->item_id}}">
                              <td class="text-center">{{$result->stock_id}}<input type="hidden" name="stock_id[]" value="{{$result->stock_id}}"></td>
                              <td class="text-center">{{$result->description}}</td>
                              <td><input class="form-control text-center no_units" stock-id="{{$result->stock_id}}" min="0" data-id="{{$result->item_id}}" id="qty_{{$result->item_id}}" name="item_quantity[]" data-invoiced ="{{$unShiptedQty}}" value="{{$unShiptedQty}}" data-tax="{{$result->tax_rate}}" type="text"><input name="item_id[]" value="{{$result->item_id}}" type="hidden"></td>
                              <td class="text-center">
                                {{$result->unit_price}}
                                <input class="form-control text-center unitprice" data-id="{{$result->item_id}}" id="rate_id_{{$result->item_id}}" value="{{$result->unit_price}}" data-tax="{{$result->tax_rate}}" type="hidden" name="unit_price[]">
                              </td>
                              <td class="text-center">{{$result->tax_rate}}%<input value="{{$result->tax_type_id}}" type="hidden" name="tax_id[]"></td>
                              <td class="text-center">
                                {{$result->discount_percent}}
                                <input class="form-control text-center discount" data-tax="{{$result->tax_rate}}" data-input-id="{{$result->item_id}}" id="discount_id_{{$result->item_id}}" type="hidden" value="{{$result->discount_percent}}" name="discount[]">
                              </td>
                              
                              <?php
                                $priceAmount = ($unShiptedQty*$result->unit_price);
                                $discount = ($priceAmount*$result->discount_percent)/100;
                                $newPrice = ($priceAmount-$discount);
                                $subTotalAmount += $newPrice;
                              ?>

                              <td><input amount-id="{{$result->item_id}}" class="form-control text-center amount tax_item_price_{{$result->tax_rate}}" id="amount_{{$result->item_id}}" value="{{$newPrice}}" data-tax-rate="{{$result->tax_rate}}" readonly type="text"></td>
                            </tr>
                          @endif
                        @endif
                    @endforeach

                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.table.sub_total') }}</strong></td><td align="left" colspan="2"><strong id="subTotal"></strong></td></tr>
                  <?php
                 
                    $taxAmount = 0;
                  ?>
                  @foreach($taxType as $rate=>$tax_amount)
                  @if(in_array($rate,$taxArray))
                  <tr class="tax_rate_{{str_replace('.','_',$rate)}}"><td colspan="6" align="right">{{ trans('message.invoice.plus_tax') }}({{$rate}}%)</td><td colspan="2" class="item-taxs" id="totalTaxs_{{str_replace('.','_',$rate)}}">{{$tax_amount}}</td></tr>
                  <?php
                    $taxAmount += $tax_amount;
                  ?>
                  @endif
                  @endforeach
                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.table.grand_total') }}</strong></td><td align="left" colspan="2"><input type='text' class="form-control" id = "grandTotal" value="{{($subTotalAmount+$taxAmount)}}" readonly></td></tr>
                  @endif
                  </tbody>
                </table>
                </div>
                <br><br>
              </div>
            </div>
              <!-- /.box-body -->
              <div class="col-md-12">
              <div class="form-group">
                    <label for="exampleInputEmail1">{{ trans('message.table.note') }}</label>
                    <textarea placeholder="{{ trans('message.table.description') }} ..." rows="3" class="form-control" name="comments"></textarea>
                </div>
                <a href="{{url('/shipment/list')}}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button type="submit" class="btn btn-primary btn-flat pull-right" id="btnSubmit">{{ trans('message.form.submit') }}</button>
              </div>
        </div>
        </form>
      </div>
      @else
        
        {{ trans('message.table.no_shipment') }}
      @endif
    </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">

    $(function () {
        $('#packed_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: '{{Session::get('date_format_type')}}'
        });
        $('#packed_date').datepicker('update', new Date());
    });


     // calculate amount with item quantity
    $(document).on('keyup', '.no_units', function(ev){
      var id = $(this).attr("data-id");
      var stock_id = $(this).attr("stock-id");
      var qty = parseInt($(this).val());
      var token = $("#token").val();
      var from_stk_loc = $("#loc").val();
      var order_reference = $("#order_reference").val();
      var invoice_no = $("#order_no").val();
      var invoiced_qty = $(this).attr('data-invoiced');
      console.log(invoiced_qty);
      if(invoiced_qty<qty){
        console.log('Exceeded');
          $("#quantityMessage").html("{{trans('message.table.no_shipment_quantity')}}");
          $('#btnSubmit').attr('disabled', 'disabled');
      }else{
          $("#quantityMessage").html('');
          $('#btnSubmit').removeAttr('disabled');
      }
  
      if(isNaN(qty)){
          qty = 0;
       }
       
      var rate = $("#rate_id_"+id).val();
      
      var taxRate = $(this).attr('data-tax');
      var newTaxInfo = createTaxId(taxRate);
      var price = calculatePrice(qty,rate);  

      var discountRate = parseFloat($("#discount_id_"+id).val());     
      if(isNaN(discountRate)){
          discountRate = 0;
       }
      var discountPrice = calculateDiscountPrice(price,discountRate); 
      $("#amount_"+id).val(discountPrice);
      var priceByTaxTpye = calculateTotalByTaxType(taxRate); 
      var tax = caculateTax(priceByTaxTpye,taxRate);
      $("#totalTaxs_"+newTaxInfo).html(tax);

      // Calculate subTotal
      var subTotal = calculateSubTotal();
      $("#subTotal").html(subTotal);
      // Calculate taxTotal
      var taxTotal = calculateTaxTotal();
      // Calculate GrandTotal
      var grandTotal = (subTotal + taxTotal);
      $("#grandTotal").val(grandTotal);

    });



      /**
      * Calcualte Total tax
      *@return totalTax for row wise
      */
      function calculateTaxTotal (){
          var totalTax = 0;
            $('.item-taxs').each(function() {
                totalTax += parseFloat($(this).text());
            });
            return totalTax;
      }
      
      /**
      * Calcualte Sub Total 
      *@return subTotal
      */
      function calculateSubTotal (){
        var subTotal = 0;
        $('.amount').each(function() {
            subTotal += parseInt($(this).val());
        });
        return subTotal;
      }

      /**
      * Calcualte Total pice by taxtype 
      *@return subTotal
      */
      function calculateTotalByTaxType (taxtype){
        var sum = 0;
        $('.tax_item_price_'+taxtype).each(function() {
            sum += parseFloat($(this).val());
        });
        return sum;
      }

      /**
      * Calcualte price
      *@return price
      */
      function calculatePrice (qty,rate){
         var price = (qty*rate);
         return price;
      }   
      // calculate tax 
      function caculateTax(p,t){
       var tax = (p*t)/100;
       return tax;
      }   
      // calculate taxId replacing dot(.) sign with dash(-) sign
      function createTaxId(taxRate){
        var taxInfo = taxRate.toString();
        var taxId = taxInfo.split('.').join('-');
        return taxId;
      }

      // calculate discont amount
      function calculateDiscountPrice(p,d){
        var discount = [(d*p)/100];
        var result = (p-discount); 
        return result;
      }


    $(document).ready(function(){
        var subTotal = calculateSubTotal();
        $("#subTotal").text(subTotal);
      });

// Item form validation
    $('#shipmentForm').validate({
        rules: {
            packed_date: {
                required: true
            }                   
        }
    });

    </script>
@endsection