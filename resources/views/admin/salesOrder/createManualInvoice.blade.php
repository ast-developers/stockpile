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
          <h4 class="text-info">{{ trans('message.invoice.order_no') }} # {{$saleData->reference}}</h4>
          <div class="clearfix"></div>
          <form action="{{url('order/save-manual-invoice')}}" method="POST" id="salesForm">  
          <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
          <input type="hidden" value="{{$saleData->order_no}}" name="order_no" id="order_no">
          <input type="hidden" value="{{$saleData->reference}}" name="order_reference" id="order_reference">
          <div class="row">
            
            <div class="col-md-3">
              <div class="form-group">
                <label for="exampleInputEmail1">{{ trans('message.form.customer') }}<span class="text-danger"> *</span></label>
                <select class="form-control select2" name="debtor_no" id="customer" disabled>
                <option value="">{{ trans('message.form.select_one') }}</option>
                @foreach($customerData as $data)
                  <option value="{{$data->debtor_no}}" <?= ($data->debtor_no == $saleData->debtor_no) ? 'selected' : ''?> >{{$data->name}}</option>
                @endforeach
                </select>
                <input type="hidden" name="debtor_no" value="{{$saleData->debtor_no}}" />
              </div>
            </div>
            <div class="col-md-3">
              <!-- /.form-group -->
              <div class="form-group">
                <label for="exampleInputEmail1">{{ trans('message.form.customer_branch') }}<span class="text-danger"> *</span></label>
                <select class="form-control select2" name="branch_id" id="branch" disabled>
                <option value="">{{ trans('message.form.select_one') }}</option>
                @if(!empty($branchs))
                  @foreach($branchs as $branch)
                  <option value="{{$branch->branch_code}}" <?= ($branch->branch_code == $saleData->branch_id ? 'selected':'')?>>{{$branch->br_name}}</option>
                  @endforeach
                @endif
                </select>
                <input type="hidden" name="branch_id" value="{{$saleData->branch_id}}" />
              </div>
              <!-- /.form-group -->
            </div>              

            <div class="col-md-3">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.form.from_location') }}<span class="text-danger"> *</span></label>
                     <select class="form-control select2" name="from_stk_loc" id="loc">
                    @foreach($locData as $data)
                      <option value="{{$data->loc_code}}" <?= ($data->loc_code == $saleData->from_stk_loc ? 'selected':'')?>>{{$data->location_name}}</option>
                    @endforeach
                    </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>{{ trans('message.table.date') }}<span class="text-danger"> *</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control" id="datepicker" type="text" name="ord_date" value="<?= isset($saleData->ord_date) ? $saleData->ord_date :'' ?>">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.extra_text.payment_method') }}<span class="text-danger"> *</span></label>
                     <select class="form-control select2" name="payment_id">
                    
                    @foreach($payments as $payment)
                      <option value="{{$payment->id}}" <?= ($payment->id == $saleData->payment_id) ? 'selected' : ''?>>{{$payment->name}}</option>
                    @endforeach
                    </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.form.payment_term') }}<span class="text-danger"> *</span></label>
                    <select class="form-control select2" name="payment_term">
                    @foreach($paymentTerms as $term)
                      <option value="{{$term->id}}" <?= ($term->defaults == 1 ? 'selected':'')?>>{{$term->terms}}</option>
                    @endforeach
                    </select>
              </div>
            </div> 

            <div class="col-md-3">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.table.reference') }}<span class="text-danger"> *</span></label>
                <div class="input-group">
                   <div class="input-group-addon">INV-</div>
                   <input id="reference_no" class="form-control" value="{{ sprintf("%04d", $invoice_count+1)}}" type="text">
                   <input type="hidden"  name="reference" id="reference_no_write" value="INV-{{ sprintf("%04d", $invoice_count+1)}}">
                </div>
              </div>
              <span id="errMsg" class="text-danger"></span>
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
                    <th style="width: 40px">{{ trans('message.table.action') }}</th>
                  </tr>

                  @if(count($invoiceData)>0)
                  <?php
                    $totalPrice = 0;
                    $qtyStatus = 0;

                  ?>
                    @foreach($invoiceData as $k=>$result)
                        @if($result->item_rest > 0)
                        <?php
                        $qtyAvailable = getAvailableQtyByLocation($result->stock_id,$result->location);
                        ?>
                        <?php
                        if($qtyAvailable < $result->item_rest){
                         $qtyStatus = 1;
                        }
                        ?>

                        <tr class="<?= ($qtyAvailable < $result->item_rest) ? 'insufficient' : '' ?>" data-tax_type="{{$result->tax_rate}}" id="rowid{{$result->item_id}}">
                          <td class="text-center">{{$result->stock_id}}<input type="hidden" name="stock_id[]" value="{{$result->stock_id}}"></td>
                          <td class="text-center">{{$result->description}}<input type="hidden" name="description[]" value="{{$result->description}}"></td>
                          <td><input class="form-control text-center no_units" stock-id="{{$result->stock_id}}" min="0" data-id="{{$result->item_id}}" id="qty_{{$result->item_id}}" name="item_quantity[]" value="{{$result->item_rest}}" data-tax="{{$result->tax_rate}}" type="text"><input name="item_id[]" value="{{$result->item_id}}" type="hidden"></td>
                          <td class="text-center"><input min="0" class="form-control text-center unitprice" name="unit_price[]" data-id="{{$result->item_id}}" id="rate_id_{{$result->item_id}}" value="{{$result->unit_price}}" data-tax="{{$result->tax_rate}}" type="text" readonly></td>
                          <td class="text-center">{{$result->tax_rate}}%<input name="tax_id[]" value="{{$result->tax_type_id}}" type="hidden"></td>
                          <td class="text-center"><input class="form-control text-center discount" name="discount[]" data-tax="{{$result->tax_rate}}" data-input-id="{{$result->item_id}}" id="discount_id_{{$result->item_id}}" type="text" value="{{$result->discount_percent}}" readonly></td>
                          
                          <?php
                            $priceAmount = ($result->item_rest*$result->unit_price);
                            $discount = ($priceAmount*$result->discount_percent)/100;
                            $newPrice = ($priceAmount-$discount);
                            $totalPrice += $newPrice; 
                          ?>

                          <td><input amount-id="{{$result->item_id}}" class="form-control text-center amount tax_item_price_{{$result->tax_rate}}" id="amount_{{$result->item_id}}" value="{{$newPrice}}" name="item_price[]" data-tax-rate="{{$result->tax_rate}}" readonly="" type="text"></td>
                          <td class="text-center"><button id="{{$result->item_id}}" class="btn btn-xs btn-danger delete_item dfdf"><i class="glyphicon glyphicon-trash"></i></button></td>
                        </tr>
                       @endif
                    @endforeach

                    <?php
                      $tax = 0;
                    ?>
                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.table.sub_total') }}</strong></td><td align="left" colspan="2"><strong id="subTotal"></strong></td></tr>
                  @foreach($taxType as $rate=>$tax_amount)
                  <tr class="tax_rate_{{str_replace('.','_',$rate)}}"><td colspan="6" align="right">{{ trans('message.invoice.plus_tax') }}({{$rate}}%)</td><td colspan="2" class="item-taxs" id="totalTaxs_{{str_replace('.','_',$rate)}}">{{$tax_amount}}</td></tr>
                    <?php
                      $tax += $tax_amount;
                    ?>
                  @endforeach

                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.table.grand_total') }}</strong></td><td align="left" colspan="2"><input type='text' name="total" class="form-control" id = "grandTotal" value="{{$totalPrice+$tax}}" readonly></td></tr>
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
                <button type="submit" class="btn btn-primary btn-flat pull-right" id="btnSubmit" <?= ($qtyStatus == 1) ? 'disabled' : '' ?>>{{ trans('message.form.submit') }}</button>
              </div>
        </div>
        </form>
            <!-- /.col -->   
            <!-- /.col -->
      </div>
          <!-- /.row -->
    </div>
        <!-- /.box-body -->
      <!-- /.box -->

    </section>
@endsection
@section('js')
    <script type="text/javascript">

      var refNo ='INV-'+$("#reference_no").val();
      $("#reference_no_write").val(refNo);
      var token = $("#token").val();

    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        $('#datepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: '{{Session::get('date_format_type')}}'
        });
        $('#datepicker').datepicker('update', new Date()); 

        // Chech available quantity with selected location
        $("#loc").change(function(){
          var selectedLocation = $(this).val();
          var itemArr = [];
        $("#salesInvoice tr.nr").each(function() {
            var item_qty = $(this).find("input.no_units").val();
            var stockid = $(this).find("input.no_units").attr("stock-id");

        itemArr.push({
           stockid:  stockid,
           qty: item_qty 
           
        });

        });
      // check item quantity in store location
      $.ajax({
        method: "POST",
        url: SITE_URL+"/sales/quantity-validation-with-localtion",
        data: { "location": selectedLocation,'itemInfo':itemArr,"_token":$("#token").val() }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);
          //console.log(data);
          if(data.status_no == 0){
            $("#quantityMessage").html(data.item);
            $('#btnSubmit').attr('disabled', 'disabled');
          }else if(data.status_no == 1){
            $("#quantityMessage").html('');
            $('#btnSubmit').removeAttr('disabled');
          }

        });
      });
    })


     // calculate amount with item quantity
    $(document).on('keyup', '.no_units', function(ev){
      var id = $(this).attr("data-id");
      var stock_id = $(this).attr("stock-id");
      var qty = parseInt($(this).val());
      var token = $("#token").val();
      var from_stk_loc = $("#loc").val();
      var order_reference = $("#order_reference").val();
      var invoice_no = $("#order_no").val();
      //console.log(stock_id);
      // check item quantity in store location
      $.ajax({
        method: "POST",
        url: SITE_URL+"/sales/quantity-validation-edit-invoice",
        data: { "item_id": id,'invoice_no':invoice_no,'stock_id':stock_id, "location_id": from_stk_loc,'qty':qty,'order_reference':order_reference,"_token":token }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);
          
          if(data.status_no == 0){
            $("#quantityMessage").html("{{trans('message.invoice.item_insufficient_message')}}");
            $('#btnSubmit').attr('disabled', 'disabled');
            $("#rowid"+id).addClass("insufficient");
          }else if(data.status_no == 1){
            $("#quantityMessage").html('');
            $('#btnSubmit').removeAttr('disabled');
          }
        });


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

// Item form validation
    $('#salesForm').validate({
        rules: {
            debtor_no: {
                required: true
            },
            from_stk_loc: {
                required: true
            },
            ord_date:{
               required: true
            },
            reference:{
              required:true
            },
            payment_id:{
              required:true
            },
            branch_id:{
              required:true
            }                    
        }
    });

    // Delete item row
    $(document).ready(function(e){
      $('#salesInvoice').on('click', '.delete_item', function(event) {
        event.preventDefault();
            var v = $(this).attr("id");

            //console.log(v);return;
            $(this).closest("tr").remove();

            var itemTaxRow = $(this).closest('tr').data('tax_type');
           
            var count = $('tr[data-tax_type="'+itemTaxRow+'"]').size();
          
           if(count == 0){

               $("tr.tax_rate_"+itemTaxRow).remove();

                var subTotal = calculateSubTotal();
                $("#subTotal").html(subTotal);
                // Calculate taxTotal
                var taxTotal = calculateTaxTotal();
                // Calculate GrandTotal
                var grandTotal = (subTotal + taxTotal);
                $("#grandTotal").val(grandTotal);
            }
            
            var subTotal = calculateSubTotal();
            $("#subTotal").html(subTotal);
            
            var newTaxInfo =createTaxId(itemTaxRow);
            var priceByTaxTpye = calculateTotalByTaxType(itemTaxRow); 
            var tax = caculateTax(priceByTaxTpye,itemTaxRow);
            $("#totalTaxs_"+newTaxInfo).html(tax);
           
            var taxTotal = calculateTaxTotal();
            // Calculate GrandTotal
            var grandTotal = (subTotal + taxTotal);
            $("#grandTotal").val(grandTotal);           
          var count = $('#purchaseInvoice tr.insufficient').length;
          
          if(count==0){
           $("#quantityMessage").hide();
            $('#btnSubmit').removeAttr('disabled');
          }

          
        });


    });

    $(document).ready(function(){
        var subTotal = calculateSubTotal();
        $("#subTotal").text(subTotal);
      });

    $(document).on('keyup', '#reference_no', function () {
        var ref = 'INV-'+$(this).val();
        $("#reference_no_write").val(ref);
        var token = $("#token").val();
     
      $.ajax({
        method: "POST",
        url: SITE_URL+"/sales/reference-validation",
        data: { ref: ref,_token:token }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);
          if(data.status_no == 1){
            $("#errMsg").html("{{ trans('message.invoice.exist') }}");
          }else if(data.status_no == 0){
            $("#errMsg").html("{{ trans('message.invoice.available') }}");
          }
        });
    });

    </script>
@endsection