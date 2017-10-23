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
        <form action="{{url('purchase/save')}}" method="POST" id="purchaseForm">  
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <div class="row">
            
            <div class="col-md-3">
              <!-- /.form-group -->
              <div class="form-group">
                <label class="require control-label">{{ trans('message.form.supplier') }}</label>
                <select class="form-control select2" style="width: 100%;" name="supplier_id" id="cus">
                <option value="">{{ trans('message.form.select_one') }}</option>
                @foreach($suppData as $data)
                  <option value="{{$data->supplier_id}}">{{$data->supp_name}}</option>
                @endforeach
                </select>
              </div>
              <!-- /.form-group -->
            </div>
            
            <div class="col-md-3">
              <div class="form-group">
                  <label class="require control-label" for="exampleInputEmail1">{{ trans('message.form.location') }}</label>
                  <select class="form-control select2" name="into_stock_location" id="loc">
                    <option value="">{{ trans('message.form.select_one') }}</option>
                    @foreach($locData as $data)
                      <option value="{{$data->loc_code}}" >{{$data->location_name}}</option>
                    @endforeach
                    </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label require">{{ trans('message.table.date') }}:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control" id="datepicker" type="text" name="ord_date">
                </div>
                <!-- /.input group -->
              </div>
            </div>

          <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">{{ trans('message.table.reference') }}<span class="text-danger"> *</span></label>
                <div class="input-group">
                   <div class="input-group-addon">PO-</div>
                   <input id="reference_no" class="form-control" value="{{ sprintf("%04d", $order_count+1)}}" type="text">
                   <input type="hidden"  name="reference" id="reference_no_write" value="">
                </div>
                <span id="errMsg" class="text-danger"></span>
            </div>
          </div> 

        </div>
        <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.form.add_item') }}</label>
                  <input class="form-control auto" list="items" placeholder="Search Item" id="search">
                <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div" tabindex="0" style="display: none; top: 60px; left: 15px; width: 520px;">
                <li>No record found!</li>
                </ul>

              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
              <div class="box-header text-center">
                <h3 class="box-title"><b>{{ trans('message.form.purchase_invoice_items') }}</b></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <div class="table-responsive">
                <table class="table table-bordered" id="purchaseInvoice">
                  <tbody>
                  <tr class="tbl_header_color dynamicRows">
                    <th width="10%" class="text-center">{{ trans('message.table.item_id') }}</th>
                    <th width="15%" class="text-center">{{ trans('message.table.description') }}</th>
                    <th width="10%" class="text-center">{{ trans('message.table.quantity') }}</th>
                    <th width="15%" class="text-center">{{ trans('message.table.rate') }}({{Session::get('currency_symbol')}})</th>
                    <th width="15%" class="text-center">{{ trans('message.table.tax') }}(%)</th>
                    <th width="10%" class="text-center">{{ trans('message.table.tax') }}({{Session::get('currency_symbol')}})</th>
                    <th width="15%" class="text-center">{{ trans('message.table.amount') }}({{Session::get('currency_symbol')}})</th>
                    <th width="10%" class="text-center">{{ trans('message.table.action') }}</th>
                  </tr>
                  <tr class="tableInfo"><td colspan="6" align="right"><strong>{{ trans('message.invoice.sub_total') }}</strong></td><td align="left" colspan="2"><strong id="subTotal"></strong></td></tr>
                  <tr class="tableInfo"><td colspan="6" align="right"><strong>{{ trans('message.invoice.totalTax') }}</strong></td><td align="left" colspan="2"><strong id="taxTotal"></strong></td></tr>
                  <tr class="tableInfo"><td colspan="6" align="right"><strong>{{ trans('message.invoice.grand_total') }}</strong></td><td align="left" colspan="2"><input type='text' name="total" class="form-control" id = "grandTotal" readonly></td></tr>
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

                <div class="box-footer">
                    <a href="{{ url('purchase/list') }}" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                    <button class="btn btn-primary pull-right btn-flat" type="submit" id='btnSubmit'>{{ trans('message.form.submit') }}</button>
                  </div>
                
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
     // console.log(taxOptionList);
      var refNo ='SO-'+$("#reference_no").val();
      $("#reference_no_write").val(refNo);

    $(document).on('keyup', '#reference_no', function () {

        var val = $(this).val();

        if(val == null || val == ''){
         $("#errMsg").html("{{ trans('message.invoice.exist') }}");
          $('#btnSubmit').attr('disabled', 'disabled');
          return;
         }else{
          $('#btnSubmit').removeAttr('disabled');
         }


        var ref = 'PO-'+$(this).val();
        $("#reference_no_write").val(ref);
      $.ajax({
        method: "POST",
        url: SITE_URL+"/purchase/reference-validation",
        data: { "ref": ref,"_token":token }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);
          if(data.status_no == 1){
            $("#errMsg").html('Already Exists!');
          }else if(data.status_no == 0){
            $("#errMsg").html('Available');
          }
        });
    });

    function in_array(search, array)
    {
      for (i = 0; i < array.length; i++)
      {
        if(array[i] ==search )
        {
          return true;
        }
      }
        return false;
    }

    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        //Date picker
        $('#datepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: '{{Session::get('date_format_type')}}'
        });
        $('#datepicker').datepicker('update', new Date());
        $('.ref').val(Math.floor((Math.random() * 100) + 1));
    })

    var stack = [];
    var token = $("#token").val();

    $( "#search" ).autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{URL::to('purchase/item-search')}}",
                dataType: "json",
                type: "POST",
                data: {
                    _token:token,
                    search: request.term
                },
                success: function(data){
                  //Start
                    if(data.status_no == 1){
                    $("#val_item").html();
                     var data = data.items;
                     $('#no_div').css('display','none');
                    response( $.map( data, function( item ) {
                        return {
                            id: item.id,
                            stock_id: item.stock_id,
                            value: item.description,
                            units: item.units,
                            price: item.price,
                            tax_rate: item.tax_rate,
                            tax_id: item.tax_id
                        }
                    }));
                  }else{
                    $('.ui-menu-item').remove();
                    $("#no_div").css('display','block');
                  }
                  //end

                 }
            })
        },

        select: function(event, ui) {
          var e = ui.item;
          if(e.id) {
              if(!in_array(e.id, stack))
              {
                stack.push(e.id);
                
                var taxAmount = (e.price*e.tax_rate)/100;
               
                var new_row = '<tr class="nr" id="rowid'+e.id+'">'+
                          '<td>'+ e.stock_id +'<input type="hidden" name="stock_id[]" value="'+e.stock_id+'"></td>'+
                          '<td>'+ e.value +'<input type="hidden" name="description[]" value="'+e.value+'"></td>'+
                          '<td><input class="form-control text-center no_units" data-id="'+e.id+'" data-rate="'+ e.price +'" type="text" id="qty_'+e.id+'" name="item_quantity[]" value="1" data-tax ="'+e.tax_rate+'"><input type="hidden" name="item_id[]" value="'+e.id+'"></td>'+
                          '<td class="text-center"><input min="0"  type="text" class="form-control text-center unitprice" name="unit_price[]" data-id = "'+e.id+'" id="rate_id_'+e.id+'" value="'+ e.price +'" data-tax="'+e.tax_rate+'"></td>'+
                          '<td class="text-center">'+taxOptionList+'</td>'+
                          '<td class="text-center taxAmount">'+taxAmount+'</td>'+
                          '<td><input class="form-control text-center amount" type="text" id="amount_'+e.id+'" value="'+e.price+'" name="item_price[]" data-tax-rate="'+e.tax_rate+'" readonly></td>'+
                          '<td class="text-center"><button id="'+e.id+'" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>'+
                          '</tr>';
                
                $(new_row).insertAfter($('table tr.dynamicRows:last'));
                
                // For tax select option
                $(function() {
                    $("#rowid"+e.id+' .taxList').val(e.tax_id);
                }); 

                // Calculate total tax

                // For tax select option
                var taxRateValue = parseFloat( $("#rowid"+e.id+' .taxList').find(':selected').attr('taxrate'));

                // Calculate subtotal
                var subTotal = calculateSubTotal();
                $("#subTotal").html(subTotal);

                // Calculate Grand Total
                var taxTotal = calculateTaxTotal();
                $("#taxTotal").text(taxTotal);

                var grandTotal = (subTotal + taxTotal);
                
                $("#grandTotal").val(grandTotal);


                $('.tableInfo').show();

              } else {
                  $('#qty_'+e.id).val( function(i, oldval) {
                      return ++oldval;
                  });
                  var q = $('#qty_'+e.id).val();
                  var r = $("#rate_id_"+e.id).val();

                $('#amount_'+e.id).val( function(i, amount) {
                    var itemPrice = (q * r);
                    return itemPrice;
                });
               var taxRateValue = parseFloat( $("#rowid"+e.id+' .taxList').find(':selected').attr('taxrate'));
               var amountByRow = $('#amount_'+e.id).val(); 
               var taxByRow = amountByRow*taxRateValue/100;
               $("#rowid"+e.id+" .taxAmount").text(taxByRow);

                // Calculate subTotal
                var subTotal = calculateSubTotal();
                $("#subTotal").html(subTotal);
                // Calculate taxTotal
                var taxTotal = calculateTaxTotal();
                $("#taxTotal").text(taxTotal);
                // Calculate GrandTotal
                var grandTotal = (subTotal + taxTotal);
                $("#grandTotal").val(grandTotal);

              }
              
              $(this).val('');
              $('#val_item').html('');
              return false;
          }
        },
        minLength: 1,
        autoFocus: true
    });


    $(document).on('change keyup blur','.check',function() {
      var row_id = $(this).attr("id").substr(2);
      var disc = $(this).val();
      var amd = $('#a_'+row_id).val();

      if (disc != '' && amd != '') {
        $('#a_'+row_id).val((parseInt(amd)) - (parseInt(disc)));
      } else {
        $('#a_'+row_id).val(parseInt(amd));
      }
      
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


    $(document).on('keyup', '.no_units', function(ev){
      var id = $(this).attr("data-id");
      var qty = parseInt($(this).val());

      var rate = $("#rate_id_"+id).val();
      
      var price = calculatePrice(qty,rate);       

      $("#amount_"+id).val(price);

      // Calculate subTotal
      var subTotal = calculateSubTotal();
      $("#subTotal").html(subTotal);
      // Calculate taxTotal

     var taxRateValue = parseFloat( $("#rowid"+id+' .taxList').find(':selected').attr('taxrate'));
     var amountByRow = $('#amount_'+id).val(); 
     var taxByRow = amountByRow*taxRateValue/100;
     $("#rowid"+id+" .taxAmount").text(taxByRow);

      var taxTotal = calculateTaxTotal();
      $("#taxTotal").text(taxTotal);
      // Calculate GrandTotal
      var grandTotal = (subTotal + taxTotal);
      $("#grandTotal").val(grandTotal);

    });

     // calculate amount with unit price
    $(document).on('keyup', '.unitprice', function(ev){
     
      var unitprice = parseFloat($(this).val());

      var id = $(this).attr("data-id");

      var qty = $("#qty_"+id).val();
      //console.log(qty);
      var rate = $("#rate_id_"+id).val();
     
      var price = calculatePrice(qty,rate);
      $("#amount_"+id).val(price);  

     var taxRateValue = parseFloat( $("#rowid"+id+' .taxList').find(':selected').attr('taxrate'));
     var amountByRow = $('#amount_'+id).val(); 
     var taxByRow = amountByRow*taxRateValue/100;
     $("#rowid"+id+" .taxAmount").text(taxByRow);

      // Calculate subTotal
      var subTotal = calculateSubTotal();
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

           var taxRateValue = parseFloat( $("#rowid"+v+' .taxList').find(':selected').attr('taxrate'));
           var amountByRow = $('#amount_'+v).val(); 
           var taxByRow = amountByRow*taxRateValue/100;
           $("#rowid"+v+" .taxAmount").text(taxByRow);

            var subTotal = calculateSubTotal();
            $("#subTotal").html(subTotal);
            
            var taxTotal = calculateTaxTotal();
            $("#taxTotal").text(taxTotal);
            // Calculate GrandTotal
            var grandTotal = (subTotal + taxTotal);
            $("#grandTotal").val(grandTotal);           

        });
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
            subTotal += parseInt($(this).val());
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


// Item form validation
    $('#purchaseForm').validate({
        rules: {
            supplier_id: {
                required: true
            },
            into_stock_location: {
                required: true
            },
            ord_date:{
               required: true
            }                       
        }
    });

    </script>
@endsection