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
          <form action="{{url('shipment/update')}}" method="POST">  
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token"> 
            <input type="hidden" value="{{$shipment_id}}" name="shipment_id" id="shipment_id">
            <div class="row">
              <div class="col-md-4">
                <strong>{{ Session::get('company_name') }}</strong>
                <h5 class="">{{ Session::get('company_street') }}</h5>
                <h5 class="">{{ Session::get('company_city') }}, {{ Session::get('company_state') }}</h5>
                <h5 class="">{{ Session::get('company_country_id') }}, {{ Session::get('company_zipCode') }}</h5>
              </div>

              <div class="col-md-4">
                <strong>{{ trans('message.extra_text.shiptment_to') }}</strong>
                <h5>{{ !empty($orderInfo->br_name) ? $orderInfo->br_name : ''}}</h5>
                <h5>{{ !empty($orderInfo->shipping_street) ? $orderInfo->shipping_street :'' }}</h5>
                <h5>{{ !empty($orderInfo->shipping_city) ? $orderInfo->shipping_city : ''}}{{ !empty($orderInfo->shipping_state) ? ', '.$orderInfo->shipping_state : ''}}</h5>
                <h5>{{ !empty($orderInfo->shipping_country_id) ? $orderInfo->shipping_country_id :''}}{{ !empty($orderInfo->shipping_zip_code) ? ', '.$orderInfo->shipping_zip_code : ''}}</h5>
              </div>
              <div class="col-md-4">
                 <strong>{{ trans('message.invoice.shift_no')}} # <a href="{{url('shipment/view-details/'.$orderInfo->order_no.'/'.$shipment_id)}}">{{ sprintf("%04d", $shipment_id) }}</a></strong>
                <h5>{{ trans('message.extra_text.location')}} : {{getDestinatin($orderInfo->from_stk_loc)}}</h5>
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
                        <th width="30%" class="text-center">{{ trans('message.table.description') }}</th>
                        <th width="10%" class="text-center">{{ trans('message.table.quantity') }}</th>
                        <th width="10%" class="text-center">{{ trans('message.table.rate') }}</th>
                        <th width="10%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                         <th class="text-center" width="10%">{{ trans('message.table.discount') }}(%)</th>
                        <th width="10%" class="text-center">{{ trans('message.table.amount') }}</th>
                      </tr>
                        <?php
                          $taxAmount      = 0;
                          $subTotalAmount = 0;
                          $qtyTotal       = 0;
                          $priceAmount    = 0;
                          $discount       = 0;
                          $discountPriceAmount = 0;
                        ?>
                        @foreach($shipmentItem as $k=>$result)
                             <?php
                              $price = ($result->quantity*$result->unit_price);
                              $discount =  ($result->discount_percent*$price)/100;
                              $discountPriceAmount = ($price-$discount);
                              $qtyTotal +=$result->quantity; 
                              $subTotalAmount += $discountPriceAmount; 
                             ?> 
                                <tr>
                                  <td class="text-center">{{$result->description}}</td>
                                  <td>
                                    <input class="form-control text-center no_units" order-no="{{$result->order_no}}" stock-id="{{$result->stock_id}}" min="0" data-id="{{$result->item_id}}" id="qty_{{$result->item_id}}" name="item_quantity[]"  value="{{$result->quantity}}" item-shifted="{{$result->quantity}}" data-tax="{{$result->tax_rate}}" type="text">
                                    <input name="item_id[]" value="{{$result->item_id}}" type="hidden">
                                    <input name="stock_id[]" value="{{$result->stock_id}}" type="hidden">
                                    <input name="previous_qty[]" value="{{$result->quantity}}" type="hidden">
                                    <input name="order_no[]" value="{{$result->order_no}}" type="hidden">
                                  </td>
                                  <td class="text-center">
                                    {{$result->unit_price}}
                                    <input class="form-control text-center unitprice" data-id="{{$result->item_id}}" id="rate_id_{{$result->item_id}}" value="{{$result->unit_price}}" data-tax="{{$result->tax_rate}}" type="hidden" name="unit_price[]">
                                  </td>
                                  <td class="text-center">{{$result->tax_rate}}%<input value="{{$result->tax_type_id}}" type="hidden" name="tax_id[]"></td>
                                  <td class="text-center">
                                    {{$result->discount_percent}}
                                    <input class="form-control text-center discount" data-tax="{{$result->tax_rate}}" data-input-id="{{$result->item_id}}" id="discount_id_{{$result->item_id}}" type="hidden" value="{{$result->discount_percent}}" name="discount[]">
                                  </td>
                                  
                                  <td><input amount-id="{{$result->item_id}}" class="form-control text-center amount tax_item_price_{{$result->tax_rate}}" id="amount_{{$result->item_id}}" value="{{$discountPriceAmount}}" data-tax-rate="{{$result->tax_rate}}" readonly type="text"></td>
                                </tr>
                        @endforeach
                      <tr class="tableInfos"><td colspan="5" align="right"><strong>{{ trans('message.table.sub_total') }}</strong></td><td align="center" colspan="2"><strong id="subTotal"></strong></td></tr>
                        @foreach($taxInfo as $rate=>$tax_amount)
                        <tr class="tax_rate_{{str_replace('.','_',$rate)}}">
                          <td colspan="5" align="right">{{ trans('message.invoice.plus_tax') }}({{$rate}}%)</td>
                          <td colspan="2" align="center" class="item-taxs" id="totalTaxs_{{str_replace('.','_',$rate)}}">{{$tax_amount}}</td></tr>
                        <?php
                          $taxAmount += $tax_amount;
                        ?>
                        @endforeach                  
                      <tr class="tableInfos"><td colspan="5" align="right"><strong>{{ trans('message.table.grand_total') }}</strong></td><td colspan="2"><input type='text' class="form-control text-center" id = "grandTotal" value="{{$subTotalAmount+$taxAmount}}" readonly></td></tr>
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
    </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
    var token = $("#token").val();
    var shipmentId= $("#shipment_id").val();
     // calculate amount with item quantity
    $(document).on('keyup', '.no_units', function(ev){
      var id = $(this).attr("data-id");
      var order_no = $(this).attr("order-no");
      var stock_id = $(this).attr("stock-id");
      var qty = parseInt($(this).val());
      var shifted_qty = $(this).attr('item-shifted');
      //start check availity of quantity for shipment
        $.ajax({
          url:SITE_URL+'/shipment/quantity-validation',
          method:'POST',
          data  :{stock_id:stock_id,token:token,shipment_id:shipmentId,shifted_qty:shifted_qty,new_qty:qty,order_no:order_no}
        }).done(function(data){
           var data = jQuery.parseJSON(data);
          if(data.status_no == 0){
            $("#quantityMessage").html('Item quantity not available.');
            $('#btnSubmit').attr('disabled', 'disabled');
          }else if(data.status_no == 1){
            $("#quantityMessage").html('');
            $('#btnSubmit').removeAttr('disabled');
          }
        });
      //end check availity of quantity for shipment
       
      var rate = $("#rate_id_"+id).val();
      
      var taxRate = $(this).attr('data-tax');
      
      var newTaxInfo = createTaxId(taxRate);

      var price = calculatePrice(qty,rate);  

      var discountRate = parseFloat($("#discount_id_"+id).val());     

      var discountPrice = calculateDiscountPrice(price,discountRate); 
    
      $("#amount_"+id).val(discountPrice);
      var priceByTaxTpye = calculateTotalByTaxType(taxRate); 
     // console.log(priceByTaxTpye);
      var tax = caculateTax(priceByTaxTpye,taxRate);
     // console.log(tax);
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
    </script>
@endsection