@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-body">
          <div class="col-md-6 col-xs-12">
              <div class="row">
            <form class="form-horizontal" action="{{ url('report/inventory-stock-on-hand') }}" method="get">
              
              <div class="col-md-5">
                  <label for="exampleInputEmail1">{{ trans('message.report.product_type') }}</label>
                    <select class="form-control select2" name="type" id="category">
                      <option value="all">{{ trans('message.report.all_type') }}</option>
                      @foreach($categoryList as $category)
                      <option value="{{$category->category_id}}" <?=isset($category->category_id) && $category->category_id == $type ? 'selected':""?>>{{$category->description}}</option>
                      @endforeach
                    </select>
              </div>
              <div class="col-md-5">
                  <label for="exampleInputEmail1">{{ trans('message.report.location') }}</label>
                    <select class="form-control select2" name="location" id="location">
                      <option value="all">{{ trans('message.report.all_location') }}</option>
                      @foreach($locationList as $location)
                      <option value="{{$location->loc_code}}" <?=isset($location->loc_code) && $location->loc_code == $location_id ? 'selected':""?>>{{$location->location_name}}</option>
                      @endforeach
                    </select>
              </div>
              <div class="col-md-2">
                <label for="pwd">&nbsp;</label>
                <button type="submit" name="btn" class="btn btn-primary btn-flat">{{ trans('message.extra_text.filter') }}</button>
              </div>
            </form>
          </div>
          </div>
          <div class="col-md-6 col-xs-12">
            <br>
            <div class="btn-group pull-right">
              <a target="_blank" href="#" title="CSV" class="btn btn-default btn-flat" id="csv">{{ trans('message.extra_text.csv') }}</a>
              <a target="_blank" href="#" title="PDF" class="btn btn-default btn-flat" id="pdf">{{ trans('message.extra_text.pdf') }}</a>
            </div>
          </div>

        </div>
        <br>
      </div><!--Top Box End-->

      <div class="box">
        <div class="box-body">
          <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold">{{$qtyOnHand}}</h3>
              <span class="text-info">{{ trans('message.report.on_hand_qty') }}</span>
          </div>
          <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold">{{ Session::get('currency_symbol').number_format($costValueQtyOnHand,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.on_hand_cost_value') }}</span>
          </div>
          <div class="col-md-3 col-xs-6 border-right">
              <h3 class="bold">{{ Session::get('currency_symbol').number_format($retailValueOnHand ,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.on_hand_retail_value') }} </span>
          </div>
          <div class="col-md-3 col-xs-6">
              <h3 class="bold">
              @if( $profitValueOnHand<0 )
              -{{Session::get('currency_symbol').number_format(abs($profitValueOnHand),2,'.',',')}}
              @else
              {{Session::get('currency_symbol').number_format(abs($profitValueOnHand),2,'.',',')}}              
              @endif
              </h3>
              <span class="text-info">{{ trans('message.report.on_hand_profit_value') }}</span>
          </div>          

        </div>
        <br>
      </div><!--Top Box End-->
      <!-- Default box -->
      <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="itemList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">{{ trans('message.report.product') }}</th>
                  <th class="text-center">{{ trans('message.report.stock_id') }}</th>
                  <th class="text-center">{{ trans('message.report.in_stock') }}</th>
                  <th class="text-center">{{ trans('message.report.mac') }}</th>
                  <th class="text-center">{{ trans('message.report.retail_price') }}</th>
                  <th class="text-center">{{ trans('message.report.in_value') }}</th>
                  <th class="text-center">{{ trans('message.report.retail_value') }}</th>
                  <th class="text-center">{{ trans('message.report.profit_value') }}</th>
                  <th class="text-center">{{ trans('message.report.profit_margin') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($itemList as $item)
                <?php
                  $mac = 0;
                  $profit_margin = 0;
                  if($item->received_qty !=0){
                   $mac = $item->cost_amount/$item->received_qty;
                  }
                  $in_value = $item->available_qty*$mac;
                  $retail_value = $item->available_qty*$item->retail_price;
                  $profit_value = ($retail_value-$in_value);
                  if($in_value !=0){
                  $profit_margin = ($profit_value*100/$in_value); 
                  }
                ?>
                <tr>
                  <td class="text-center">{{ $item->description }}</td>
                  <td class="text-center">{{ $item->stock_id }}</td>
                  <td class="text-center">{{ $item->available_qty }}</td>
                  <td class="text-center">{{ Session::get('currency_symbol').number_format($mac,2,'.',',') }}</td>
                  <td class="text-center">{{ Session::get('currency_symbol').number_format($item->retail_price,2,'.',',') }}</td>
                  <td class="text-center">{{ Session::get('currency_symbol').number_format(abs($in_value),2,'.',',') }}</td>
                  <td class="text-center">{{ Session::get('currency_symbol').number_format(abs($retail_value),2,'.',',') }}</td>
                  <td class="text-center">{{ Session::get('currency_symbol').number_format(abs($profit_value),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format($profit_margin,2,'.',',') }}%</td>
                </tr>
               @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
      <!-- /.box -->
    </section>

@include('layouts.includes.message_boxes')
@endsection
@section('js')
<script type="text/javascript">
  $(function () {
  $(".select2").select2({});
    $("#itemList").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 6,
        "orderable": true
        } ],
        "language": '{{Session::get('dflt_lang')}}',
        "pageLength": '{{Session::get('row_per_page')}}'
    });
   
    // Create pdf
   $('#pdf').on('click', function(event){
      event.preventDefault();
      var category = $('#category').val();
      var location = $('#location').val();
      var token = $("#token").val();
      window.location = SITE_URL+"/report/inventory-stock-on-hand-pdf?type="+category+"&location="+location;
    });

   $('#csv').on('click', function(event){
      event.preventDefault();
      var category = $('#category').val();
      var location = $('#location').val();
      var token = $("#token").val();
      window.location = SITE_URL+"/report/inventory-stock-on-hand-csv?type="+category+"&location="+location;
    });

  });

    </script>
@endsection