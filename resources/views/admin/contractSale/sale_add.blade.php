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
        <form action="{{url('contract/sales/save')}}" method="POST" id="salesForm">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <div class="row">
            
            <div class="col-md-3">
              <!-- /.form-group -->
              <div class="form-group">
                <label for="exampleInputEmail1">{{ trans('message.form.customer') }}<span class="text-danger"> *</span></label>
                <select class="form-control select2" name="debtor_no" id="customer">
                <option value="">{{ trans('message.form.select_one') }}</option>
                @foreach($customerData as $data)
                  <option value="{{$data->debtor_no}}">{{$data->name}}</option>
                @endforeach
                </select>
              </div>
              <!-- /.form-group -->
            </div>
            <div class="col-md-3">
              <!-- /.form-group -->
              <div class="form-group">
                <label for="exampleInputEmail1">{{ trans('message.form.customer_branch') }}</label>
                <select class="form-control select2" name="branch_id" id="branch">
                </select>
              </div>
              <!-- /.form-group -->
            </div>            

            <div class="col-md-3">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.form.from_location') }}</label>
                     <select class="form-control select2" name="from_stk_loc" id="loc">
                   
                    @foreach($locData as $data)
                      <option value="{{$data->loc_code}}" <?= ($data->inactive =="1" ? 'selected':'')?>>{{$data->location_name}}</option>
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
                  <input class="form-control" id="datepicker" type="text" name="contract_date">
                </div>
                <!-- /.input group -->
              </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">{{ trans('message.extra_text.payment_method') }}</label>
                    <select class="form-control select2" name="payment_id">

                        @foreach($payments as $payment)
                            <option value="{{$payment->id}}" <?= ($payment->defaults =="1" ? 'selected':'')?>>{{$payment->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.form.sales_type') }}</label>
                     <select class="form-control select2" name="sales_type" id="sales_type_id">
                    @foreach($salesType as $key=>$saleType)
                      <option value="{{$saleType->id}}" <?= ($saleType->defaults== 1 )?'selected':''?>>{{$saleType->sales_type}}</option>
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
                   <input type="hidden"  name="reference" id="reference_no_write" value="">
                </div>
                <span id="errMsg" class="text-danger"></span>
            </div>
          </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label for="exampleInputEmail1">{{ trans('message.table.discount_type') }}</label>
                    <select class="form-control select2" name="discount_type" id="discount_type">
                        <option value="1">Per Item</option>
                        <option value="2">Per Order</option>
                    </select>
                </div>
            </div>

        </div>


        <br>

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
                <table class="table table-bordered" id="purchaseInvoice">
                  <tbody>

                  <tr class="tbl_header_color">
                    <th class="text-center">{{ trans('message.table.description') }}</th>
                    <th width="10%" class="text-center">{{ trans('message.table.quantity') }}</th>
                    <th width="10%" class="text-center">{{ trans('message.table.rate') }}({{Session::get('currency_symbol')}})</th>
                      <th width="15%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                      <th width="10%" class="text-center">{{ trans('message.table.tax') }}({{Session::get('currency_symbol')}})</th>
                      <th width="10%" class="text-center">{{ trans('message.table.discount') }}(%)</th>
                      <th  class="text-center">{{ trans('message.table.amount') }}({{Session::get('currency_symbol')}})</th>
                    <th  class="text-center">{{ trans('message.table.action') }}</th>
                  </tr>

                  <tr class="tbl_header_color dynamicRows" id="rowid_1">
                      <td class="text-center">
                        <input type="text" name="description[]" id="description_1" value="" class="form-control text-center desc">
                      </td>
                      <td class="text-center">
                          <input type="text" name="item_quantity[]" data-id="1" id="qty_1" value="1" class="form-control text-center no_units">
                      </td>
                      <td width="10%">
                          <input min="0"  type="text" class="form-control text-center unitprice" name="unit_price[]" data-id = "1" id="rate_id_1" value="0">
                      </td>
                      <td class="text-center">
                          <select class="form-control taxList" name="tax_id[]" id="tax_id_1">
                              {!! $tax_type !!}
                          </select>
                      </td>
                      <td class="text-center taxAmount" id="tax_amt_1">0</td>
                      <td class="text-center"><input type="text" class="form-control text-center discount" name="discount[]" data-input-id="1" id="discount_id_1" max="100" min="0" value="0"></td>
                      <td>
                          <input class="form-control text-center amount" type="text" amount-id = "1" id="amount_1" value="0" name="item_price[]" readonly>
                      </td>
                      <td class="text-center">
                          <button id="1" class="btn btn-xs btn-danger delete_item" disabled><i class="glyphicon glyphicon-trash"></i></button>
                      </td>

                  </tr>

                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.table.discount') }}(%)</strong></td><td align="left" colspan="2"><input type="text" class="form-control" id="perOrderDiscount" name="perOrderDiscount" value="0" max="100" min="0" readonly></td></tr>

                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.table.sub_total') }}({{Session::get('currency_symbol')}})</strong></td><td align="left" colspan="2"><strong id="subTotal">0</strong></td></tr>

                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.invoice.totalTax') }}({{Session::get('currency_symbol')}})</strong></td><td align="left" colspan="2"><strong id="taxTotal">0</strong></td></tr>

                  <tr class="tableInfos"><td colspan="6" align="right"><strong>{{ trans('message.table.grand_total') }}({{Session::get('currency_symbol')}})</strong></td><td align="left" colspan="2"><input type='text' name="total" class="form-control" id="grandTotal" value="0" readonly></td></tr>

                  </tbody>
                </table>
                    <div class="add-more-action"><button type="button" id="add_more">Add more</button> </div>
                </div>

                  <input type="hidden" value="1" id="rowCount" name="rowCount">
                  <input type="hidden" value="1" id="arrayCount" name="arrayCount">

                <br><br>
                
              </div>
            </div>
              <!-- /.box-body -->
              <div class="col-md-12">
              <div class="form-group">
                    <label for="exampleInputEmail1">{{ trans('message.table.note') }}</label>
                    <textarea placeholder="{{ trans('message.table.description') }} ..." rows="3" class="form-control" name="comments"></textarea>
                </div>
                <a href="{{url('/order/list')}}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button type="submit" class="btn btn-primary btn-flat pull-right" id="btnSubmit">{{ trans('message.form.submit') }}</button>
              </div>
        </div>

        </form>
      </div>
          <!-- /.row -->
    </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
        
        $(document).ready(function() {
            // Calculate subtotal
            var subTotal = calculateSubTotal();
            var perOrderDiscount = parseFloat($('#perOrderDiscount').val());
            subTotal = calculatePerOrderDiscount(subTotal, perOrderDiscount);
            $("#subTotal").html(subTotal);

            //Calculate tax total
            var taxTotal = calculateTaxTotal();
            $("#taxTotal").text(taxTotal);

            //Calculate grand total
            var grandTotal = (subTotal + taxTotal);
            $("#grandTotal").val(grandTotal);
        });

    $(function() {
        $(document).on('click', function(e) {
            if (e.target.id === 'no_div') {
                $('#no_div').hide();
            } else {
                $('#no_div').hide();
            }

        })
    });

        var taxOptionList = "{!! $tax_type !!}";
        $(document).ready(function(){
          var refNo ='INV-'+$("#reference_no").val();
          $("#reference_no_write").val(refNo);
          $("#customer").on('change', function(){
          var debtor_no = $(this).val();
          $.ajax({
            method: "POST",
            url: SITE_URL+"/sales/get-branches",
            data: { "debtor_no": debtor_no,"_token":token }
          })
            .done(function( data ) {
              var data = jQuery.parseJSON(data);
              if(data.status_no == 1){
                $("#branch").html(data.branchs);
              }
            });
          });
        });


        $(document).on('keyup', '#reference_no', function () {
            var val = $(this).val();

            if(val == null || val == ''){
             $("#errMsg").html("{{ trans('message.invoice.exist') }}");
              $('#btnSubmit').attr('disabled', 'disabled');
              return;
             }else{
              $('#btnSubmit').removeAttr('disabled');
             }

            var ref = 'INV-'+$(this).val();
            $("#reference_no_write").val(ref);
          $.ajax({
            method: "POST",
            url: SITE_URL+"/contracts/reference-validation",
            data: { "ref": ref,"_token":token }
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



        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2({});

            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: '{{Session::get('date_format_type')}}'
            });

            $('.ref').val(Math.floor((Math.random() * 100) + 1));

             $('#datepicker').datepicker('update', new Date());
        })

        var stack = [];
        var token = $("#token").val();

        //Add more button click
        $(document).on('click', '#add_more', function(){
            var i  = parseInt($("#rowCount").val());
            var x = parseInt($("#arrayCount").val());

            var j=i+1;

            $("#rowCount").val(i+1);
            $("#arrayCount").val(x+1);

            var new_row = '<tr id="rowid_'+j+'">'+
                    '<td class="text-center"><input type="text" name="description[]" id="description_'+j+'" value="" class="form-control text-center desc"></td>'+
                    '<td><input class="form-control text-center no_units" min="0" data-id="'+j+'" data-rate="'+j+'" type="text" id="qty_'+j+'" name="item_quantity[]" value="1"></td>'+
                    '<td class="text-center"><input min="0"  type="text" class="form-control text-center unitprice" name="unit_price[]" data-id = "'+j+'" id="rate_id_'+j+'" value="0"></td>'+
                    '<td class="text-center"><select class="form-control taxList" name="tax_id[]" id="tax_id_'+j+'">'+ taxOptionList +'</select></td>'+
                    '<td class="text-center taxAmount" id="tax_amt_'+j+'">0</td>'+
                    '<td class="text-center"><input type="text" class="form-control text-center discount" name="discount[]" data-input-id="'+j+'" id="discount_id_'+j+'" max="100" min="0" value="0"></td>'+
                    '<td><input class="form-control text-center amount" type="text" amount-id = "'+j+'" id="amount_'+j+'" value="0" name="item_price[]" readonly></td>'+
                    '<td class="text-center"><button id="'+j+'" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>'+
                    '</tr>';

            $(new_row).insertAfter($('table tr.dynamicRows:last'));

            //To check what discount type option is selected
            $("#discount_type").change();
        });



        $(document).ready(function() {
              $(window).keydown(function(event){
                if(event.keyCode == 13) {
                  event.preventDefault();
                  return false;
                }
              });
            });

        // price calcualtion with quantity
         $(document).ready(function(){
           $('.tableInfo').hide();
          });

         // calculate amount with item quantity
        $(document).on('keyup', '.no_units', function(ev){
          var id = $(this).attr("data-id");
          var qty = parseInt($(this).val());
          var token = $("#token").val();
          var from_stk_loc = $("#loc").val();

            if(isNaN(qty)){
              qty = 0;
            }

          var rate = $("#rate_id_"+id).val();
          var price = calculatePrice(qty,rate);

          var discountRate = parseFloat($("#discount_id_"+id).val());
          if(isNaN(discountRate)){
              discountRate = 0;
           }

          var discountPrice = calculateDiscountPrice(price,discountRate);
          $("#amount_"+id).val(discountPrice);

         var taxRateValue = parseFloat( $("#rowid_"+id+' .taxList').find(':selected').attr('taxrate'));
         var amountByRow = $('#amount_'+id).val();
         var taxByRow = amountByRow*taxRateValue/100;
         $("#rowid_"+id+" .taxAmount").text(taxByRow);

          // Calculate subTotal
          var subTotal = calculateSubTotal();
            var perOrderDiscount = parseFloat($('#perOrderDiscount').val());
            subTotal = calculatePerOrderDiscount(subTotal, perOrderDiscount);
          $("#subTotal").html(subTotal);

          // Calculate taxTotal
          var taxTotal = calculateTaxTotal();
          $("#taxTotal").text(taxTotal);
          // Calculate GrandTotal
          var grandTotal = (subTotal + taxTotal);
          $("#grandTotal").val(grandTotal);

        });

         // calculate amount with discount
        $(document).on('keyup', '.discount', function(ev){

          var discount = parseFloat($(this).val());

          if(isNaN(discount)){
              discount = 0;
           }

          var id = $(this).attr("data-input-id");
          var qty = $("#qty_"+id).val();
          var rate = $("#rate_id_"+id).val();
          var discountRate = $("#discount_id_"+id).val();
          var price = calculatePrice(qty,rate);
          var discountPrice = calculateDiscountPrice(price,discountRate);
          $("#amount_"+id).val(discountPrice);

         var taxRateValue = parseFloat( $("#rowid_"+id+' .taxList').find(':selected').attr('taxrate'));
         var amountByRow = $('#amount_'+id).val();
         var taxByRow = amountByRow*taxRateValue/100;
         $("#rowid_"+id+" .taxAmount").text(taxByRow);

          // Calculate subTotal
          var subTotal = calculateSubTotal();
            var perOrderDiscount = parseFloat($('#perOrderDiscount').val());
            subTotal = calculatePerOrderDiscount(subTotal, perOrderDiscount);
          $("#subTotal").html(subTotal);

          // Calculate taxTotal
          var taxTotal = calculateTaxTotal();
          $("#taxTotal").text(taxTotal);
          // Calculate GrandTotal
          var grandTotal = (subTotal + taxTotal);
          $("#grandTotal").val(grandTotal);

        });


         // calculate amount with unit price
        $(document).on('keyup', '.unitprice', function(ev){

          var unitprice = parseFloat($(this).val());

          if(isNaN(unitprice)){
              unitprice = 0;
           }

          var id = $(this).attr("data-id");
          var qty = $("#qty_"+id).val();
          var rate = $("#rate_id_"+id).val();
          var discountRate = $("#discount_id_"+id).val();

          var price = calculatePrice(qty,rate);
          var discountPrice = calculateDiscountPrice(price,discountRate);
          $("#amount_"+id).val(discountPrice);

         var taxRateValue = parseFloat( $("#rowid_"+id+' .taxList').find(':selected').attr('taxrate'));
         var amountByRow = $('#amount_'+id).val();
         var taxByRow = amountByRow*taxRateValue/100;
         $("#rowid_"+id+" .taxAmount").text(taxByRow);

          // Calculate subTotal
          var subTotal = calculateSubTotal();
            var perOrderDiscount = parseFloat($('#perOrderDiscount').val());
            subTotal = calculatePerOrderDiscount(subTotal, perOrderDiscount);
          $("#subTotal").html(subTotal);
          // Calculate taxTotal
          var taxTotal = calculateTaxTotal();
          $("#taxTotal").text(taxTotal);
          // Calculate GrandTotal
          var grandTotal = (subTotal + taxTotal);
          $("#grandTotal").val(grandTotal);

        });


        $(document).on('change', '.taxList', function(ev){
          var taxRateValue = $(this).find(':selected').attr('taxrate');
          var rowId = $(this).closest('tr').prop('id');
          var amountByRow = $("#"+rowId+" .amount").val();
          var taxByRow = amountByRow*taxRateValue/100;
          $("#"+rowId+" .taxAmount").text(taxByRow);

          // Calculate subTotal
          var subTotal = calculateSubTotal();
          var perOrderDiscount = parseFloat($('#perOrderDiscount').val());
          subTotal = calculatePerOrderDiscount(subTotal, perOrderDiscount);
          $("#subTotal").html(subTotal);

          // Calculate taxTotal
          var taxTotal = calculateTaxTotal();
          $("#taxTotal").text(taxTotal);
          // Calculate GrandTotal
          var grandTotal = (subTotal + taxTotal);
          $("#grandTotal").val(grandTotal);

        });

        // Delete item row
        $(document).ready(function(e){
          $('#purchaseInvoice').on('click', '.delete_item', function() {
                var v = $(this).attr("id");
                stack = jQuery.grep(stack, function(value) {
                  return value != v;
                });

                $(this).closest("tr").remove();

              //Decrement for array count in row
              var arrayCount = parseInt($("#arrayCount").val());
              $("#arrayCount").val(arrayCount-1)

               var taxRateValue = parseFloat( $("#rowid_"+v+' .taxList').find(':selected').attr('taxrate'));
               var amountByRow = $('#amount_'+v).val();
               var taxByRow = amountByRow*taxRateValue/100;
               $("#rowid_"+v+" .taxAmount").text(taxByRow);

                var subTotal = calculateSubTotal();
                var perOrderDiscount = parseFloat($('#perOrderDiscount').val());
                subTotal = calculatePerOrderDiscount(subTotal, perOrderDiscount);
                $("#subTotal").html(subTotal);

                var taxTotal = calculateTaxTotal();
                $("#taxTotal").text(taxTotal);
                // Calculate GrandTotal
                var grandTotal = (subTotal + taxTotal);
                $("#grandTotal").val(grandTotal);

            });
        });

        //Discount type drop down on change
        $(document).on('change', '#discount_type', function(){
            if($(this).val()=='2') {
                $('.discount').val(0);
                //reclaculate the total calculation after exclude per item discount after setting per item discount 0
                $('.discount').keyup();
                $('.discount').attr('readonly', true);
                $('#perOrderDiscount').attr('readonly', false);
            }else{
                $('.discount').attr('readonly', false);
                $('#perOrderDiscount').val(0);
                //reclaculate the total calculation after exclude per item discount after setting per item discount 0
                $('.discount').keyup();
                $('#perOrderDiscount').attr('readonly', true);
            }
        });

        //per order discount key up event
        $(document).on('keyup', '#perOrderDiscount', function(){

            // Calculate subTotal
            var subTotal = calculateSubTotal();
            var perOrderDiscount = parseFloat($(this).val());
            subTotal = calculatePerOrderDiscount(subTotal, perOrderDiscount);
            $("#subTotal").html(subTotal);

            // Calculate taxTotal
            var taxTotal = calculateTaxTotal();
            $("#taxTotal").text(taxTotal);

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
            $('.taxAmount').each(function() {
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
            subTotal += parseFloat($(this).val());
        });
        return subTotal;
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

      // calculate discont amount
      function calculateDiscountPrice(p,d){
        var discount = [(d*p)/100];
        var result = (p-discount); 
        return result;
      }

    //Calculate disccount for per order
    function calculatePerOrderDiscount(total, discount){
        var finalDiscount = (discount * total)/100;
        var totalAfterDiscount = total - finalDiscount;

        return totalAfterDiscount;
    }

    //Calculation after excluding order discount
    function excludeOrderDiscount(total, discount){
        var finalDiscount = (discount * total)/100;
        var totalAfterDiscount = total + finalDiscount;

        return totalAfterDiscount;
    }



    //Check on form submit
    $( "form" ).submit(function( event ) {

        var descBlankCount=0;

        $(".desc").each(function(){
            if($(this).val()==''){
                descBlankCount++;
            }
        });

        if(descBlankCount > 0){
            $("#quantityMessage").html("Please fill all descriptions");
            event.preventDefault();
        }else{
            $("#quantityMessage").hide();
            return;
        }
    });



// Item form validation
    $('#salesForm').validate({
        rules: {
            debtor_no: {
                required: true
            },
            from_stk_loc: {
                required: true
            },
            contract_date:{
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
            },
            'description[]':{
                required:true
            }
        }
    });

    </script>
@endsection