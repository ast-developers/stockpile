@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
     <div class="box">
      <div class="box box-default">
        <div class="box-body">
            <div class="top-bar-title padding-bottom">{{ trans('message.barcode.barcode') }}</div>
        </div>
      </div>
      </div>

      <div class="box" style="min-height: 300px;">
      <div class="box-default">
       <form action="{{url('barcode/create')}}" method="POST" id="barcodeCreate">  
        <div class="box-body">
              <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
              
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.form.add_item') }}</label>
                  <input class="form-control auto" placeholder="{{ trans('message.invoice.search_item') }}" id="search">

                <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="no_div" tabindex="0" style="display: none; top: 60px; left: 15px; width: 520px;">
                <li>No record found!</li>
                </ul>
              </div>

                <table class="table table-bordered" id="itemTable">
                  <tbody>
                  <tr class="dynamicRows tbl_header_color">
                    <th width="30%" class="text-center">{{ trans('message.barcode.product_name') }}</th>
                    <th width="10%" class="text-center">{{ trans('message.barcode.barcode_quantity') }}</th>
                    <th width="1%"  class="text-center">{{ trans('message.table.action') }}</th>
                  </tr>
                  </tbody>
                </table>

               <div class="form-group" style="margin-top: 20px;">
                <label for="sel1">Styles:</label>
                <select class="form-control select2" id="sel1" name="perpage">
                  <option value="30" <?= isset($perpage) && ($perpage==30) ? 'selected' : '' ?>>30 per sheet (2.625" x 1")</option>
                  <option value="20" <?= isset($perpage) && ($perpage==20) ? 'selected' : '' ?>>20 per sheet (4" x 1")</option>
                </select>
              </div>

               <div class="form-group col-md-6">
                  <br>
                  <label for="sel1">Print : </label>
                   <div class="checkbox" style="margin-top: -5px;">
                    <label><input type="checkbox" name="site_name">{{ trans('message.barcode.company_name') }}</label>
                    <label><input type="checkbox" name="product_name">{{ trans('message.barcode.product_name') }}</label>
                  </div>
               </div>

              <div class="form-group col-md-6">
              <br>
              <button type="submit" class="btn btn-info btn-flat pull-right">{{ trans('message.barcode.submit') }}</button>
              </div>

        </div>
        </form>
      </div>
      </div>

      @if(count($items)>0)
          <div class="box">
            <div class="box-default">
              <div class="box-body">
               <a href="javascript:void(0);" id="printBtn" class="btn btn-info btn-block">Print</a> 
               
                <?php
                  $counter = 1;
                  $strDiv='<div class="barcode">';
                ?>
                  @for($i=0;$i<count($items);$i++)
                    @for($j=0;$j<$quantities[$i];$j++)
                        <?php if($counter==1 || $counter%$perpage ==1) echo $strDiv;?>
                      <div class="item style<?= $perpage?>">
                        @if($company_name=='on')
                        <span class="barcode_site">{{ Session::get('company_name') }}</span>
                        @endif
                        @if($product_name=='on')
                        <span class="barcode_name">{{$items[$i]}}</span>
                        @endif
                        <span class="barcode_image">
                        <?php
                        $code = $stock_ids[$i]; 
                        echo '<img src="data:image/png;base64,'.DNS1D::getBarcodePNG($code, "C128") . '" alt="barcode" class="bcimg"/>';
                        ?>
                        <div>
                        <?php echo $code; ?>
                        </div>
                        </span>
                      </div>
                     
                      
                      <?php $counter++; if($counter==1 || $counter%$perpage ==1) echo '</div>';?>
                    @endfor
                  @endfor

                
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
      @endif

    </section>
@endsection
@section('js')
    <script type="text/javascript">

$(document).ready(function(){
    $("#printBtn").click(function(){
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("div.barcode").printArea( options );
    });
});

    $('.select2').select2();
    var stack = [];
    var token = $("#token").val();

    $( "#search" ).autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{URL::to('barcode/search')}}",
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
                            value: item.description,
                            stock_id:item.stock_id
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
          console.log(e);
          if(e.id) {
              if(!in_array(e.id, stack))
              {
                stack.push(e.id);
                
                var new_row = '<tr id="rowid'+e.id+'">'+
                          '<td class="text-center">'+ e.value +' <input type="hidden" name="id[]" value="'+e.id+'"><input type="hidden" name="name[]" value="'+e.value+'"><input type="hidden" name="stock_id[]" value="'+e.stock_id+'"></td>'+
                          '<td><input class="form-control text-center no_units" min="0" name="quantity[]" value="1" id="quantity_'+e.id+'"></td>'+
                          '<td><button id="'+e.id+'" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>'
                          '</tr>';

                $(new_row).insertAfter($('table tr.dynamicRows:last'));
                $('.tableInfo').show();

              } else {
                  $('#quantity_'+e.id).val( function(i, oldval) {
                      return ++oldval;
                  });

              }
              
              $(this).val('');
              $('#val_item').html('');
              return false;
          }
        },
        minLength: 1,
        autoFocus: true
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

    </script>
@endsection