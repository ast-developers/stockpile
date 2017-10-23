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
        <form action="{{url('transfer/save')}}" method="POST" id="transferForm">  
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                  <label class="require control-label" for="exampleInputEmail1">{{ trans('message.form.source') }}</label>
                  <select class="form-control select2" name="source" id="source">
                    <option value="">{{ trans('message.form.select_one') }}</option>
                    @foreach($locationList as $data)
                      <option value="{{$data->loc_code}}" >{{$data->location_name}}</option>
                    @endforeach
                    </select>
              </div>
            </div>
            
            <div class="col-md-3">
              <div class="form-group">
                  <label class="require control-label" for="exampleInputEmail1">{{ trans('message.form.destination') }}</label>
                  <select class="form-control select2" name="destination" id="destination">
                    <option value="">{{ trans('message.form.select_one') }}</option>
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
                  <input class="form-control" id="datepicker" type="text" name="transfer_date">
                </div>
                <!-- /.input group -->
              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="errorMessage" style="color:red; font-weight:bold"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.form.add_item') }}</label>
                  <input class="form-control auto" placeholder="Search Item" id="search">
                
                <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div" tabindex="0" style="display: none; top: 60px; left: 15px; width: 520px;">
                <li>No record found!</li>
                </ul>

              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
              <div class="box-header text-center">
                <h3 class="box-title"><b>{{ trans('message.form.stock_move') }}</b></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <div class="table-responsive">
                <table class="table table-bordered" id="transferTbl">
                  <tbody>
                  <tr class="tbl_header_color dynamicRows">
                    <th width="75%" class="text-center">{{ trans('message.form.item_name') }}</th>
                    <th width="20%" class="text-center">{{ trans('message.table.quantity') }}</th>
                    <th width="5%">{{ trans('message.table.action') }}</th>
                  </tr>
                  <tr class="tableInfo"><td colspan="1" align="right"><strong>{{ trans('message.invoice.total') }}</strong></td><td align="center" colspan="1" id="total_qty" style="font-weight:bold;" width="20%"></td></tr>
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
                <a href="{{URL::to('/')}}/transfer/list" class="btn btn-info btn-flat">{{ trans('message.form.cancel') }}</a>
                <button id="btnSubmit" type="submit" class="btn btn-primary btn-flat pull-right">{{ trans('message.form.submit') }}</button>
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

$(function() {
    $(document).on('click', function(e) {
        if (e.target.id === 'no_div') {
            $('#no_div').hide();
        } else {
            $('#no_div').hide();
        }

    })
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
        $('#datepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: '{{Session::get('date_format_type')}}'
        });
        $('#datepicker').datepicker('update', new Date());
    })

    var stack = [];
    var token = $("#token").val();
    $( "#search" ).autocomplete({
        source: function(request, response) {
            $.ajax({
                url: SITE_URL+'/transfer/search',
                type: "post",
                dataType: "json",
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
                var new_row = '<tr class="addedRow" id="rowid'+e.id+'">'+
                          '<td class="text-center">'+ e.value +'<input type="hidden" name="description[]" value="'+e.value+'"><input type="hidden" name="stock[]" value="'+e.stock_id+'"></td>'+
                          '<td><input class="form-control text-center no_units" stock-id="'+e.stock_id+'" id="qty_'+e.id+'" data-id="'+e.id+'" type="text" name="quantity[]" value="1"><input type="hidden" name="id[]" value="'+e.id+'"></td>'+
                          '<td class="text-center"><button id="'+e.id+'" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>'+
                          '</tr>';
                $(new_row).insertAfter($('table tr.dynamicRows:last'));
              } else {
                  $('#qty_'+e.id).val( function(i, oldval) {
                      return ++oldval;
                  });
              }
              $(this).val('');
                // Check item Quantity
                    $.ajax({
                      method: "POST",
                      url: SITE_URL+"/transfer/check-item-qty",
                      data: { "stock_id": e.stock_id,"_token":token,source:$('#source').val() }
                    })
                      .done(function( data ) {
                        var data = jQuery.parseJSON(data);
                        var addedQty = $("#qty_"+e.id).val();
                        if(addedQty > data.qty){
                            $("#errorMessage").html(data.message);
                            $("#rowid"+e.id).addClass("insufficient");
                            $('#btnSubmit').attr('disabled', 'disabled');
                        }
                      });                
                // End ehcking quantity
              var totalQty = calculateTotalQty();
              $('#total_qty').text(totalQty);
              return false;
          }
        },
        minLength: 1,
        autoFocus: true
    });


      /**
      * Calcualte Total Qty 
      *@return Total
      */
      function calculateTotalQty (){
        var total = 0;
        $('.no_units').each(function() {
            total += parseInt($(this).val());
        });
        return total;
      }

    $(document).ready(function() {
          $(window).keydown(function(event){
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
          });
        });

    // Delete item row
    $(document).ready(function(e){
      $('#transferTbl').on('click', '.delete_item', function() {
            var v = $(this).attr("id");
            stack = jQuery.grep(stack, function(value) {
              return value != v;
            });
            
            $(this).closest("tr").remove();
            var totalQty = calculateTotalQty();
            $('#total_qty').text(totalQty);       

            // enable and disable submit btn
            count_rows = $( ".insufficient" ).length;
            //console.log(count_rows);
            if(count_rows==0){
             $("#errorMessage").hide();
            $('#btnSubmit').removeAttr('disabled');             
            }
        });
    });
 
$( "#source" ).change(function() {
  stack = [];
  $("#errorMessage").text(' ');
      var source = $(this).val();
      $.ajax({
        method: "POST",
        url: SITE_URL+"/transfer/get-destination",
        data: { "source": source,"_token":token }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);

          if(data.status_no == 1){
            $("#destination").html(data.destination);
          }

        });

        deleteAddedRows();
        $('#total_qty').text('0');

});

     // calculate amount with item quantity
    $(document).on('keyup', '.no_units', function(ev){
      var stock_id = $(this).attr("stock-id");
      var qty = parseInt($(this).val());
      var token = $("#token").val();
      var source = $("#source").val();
      var id = $(this).attr("data-id");
      // check item quantity in store location
      $.ajax({
        method: "POST",
        url: SITE_URL+"/transfer/check-item-qty",
        data: { "stock_id": stock_id, "source": source,"_token":token }
      })
        .done(function( data ) {
          var data = jQuery.parseJSON(data);
          if(qty > data.qty){
              $("#errorMessage").html(data.message);
              $("#rowid"+id).addClass("insufficient");
              $('#btnSubmit').attr('disabled', 'disabled');
          }
          if(qty <= data.qty){
           $("#errorMessage").html('');
           $("#rowid"+id).removeClass("insufficient");
           $('#btnSubmit').removeAttr('disabled');
          }

        });
        var totalQty = calculateTotalQty();
        $('#total_qty').text(totalQty);
        
    });

    function deleteAddedRows(){
        $('.addedRow').each(function() {
            $(this).closest("tr").remove();
        });
    }

// Item form validation
    $('#transferForm').validate({
        rules: {
            source: {
                required: true
            },
            destination: {
                required: true
            },
            transfer_date:{
               required: true
            }                       
        }
    });
    

    </script>
@endsection