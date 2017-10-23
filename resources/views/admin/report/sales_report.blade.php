@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-body">
          <form class="form-horizontal" action="{{ url('report/sales-report') }}" method="GET" id='salesReport'>
          <div class="col-md-9 col-xs-12">
            <div class="row">
               <div class="col-md-3">
                 <div class="form-group" style="margin-right: 5px">
                  <label for="sel1">{{ trans('message.report.product') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="product" id="product">
                    <option value="all">All</option>
                    @if(!empty($productList))
                    @foreach($productList as $productItem)

                    <option value="{{$productItem->stock_id}}" <?= ($productItem->stock_id == $item) ? 'selected' : ''?>>{{$productItem->description}}</option>
                    @endforeach
                    @endif
                  </select>
                  </div>
                </div>
               </div>
               <div class="col-md-3">
                 <div class="form-group" style="margin-right: 5px">
                  <label for="sel1">{{ trans('message.form.customer') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="customer" id="customer">
                    <option value="all">All</option>
                    @if(!empty($customerList))
                    @foreach($customerList as $customerItem)
                    <option value="{{$customerItem->debtor_no}}" <?= ($customerItem->debtor_no == $customer) ? 'selected' : ''?>>{{$customerItem->name}}</option>
                    @endforeach
                    @endif
                  </select>
                  </div>
                </div>
               </div>

               <div class="col-md-3">
                 <div class="form-group">
                  <label for="location">{{ trans('message.form.location') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="location" id="location">
                    <option value="all">All</option>
                    @if(!empty($locationList))
                    @foreach($locationList as $locationItem)
                    <option value="{{$locationItem->loc_code}}" <?= ($locationItem->loc_code == $location) ? 'selected' : ''?>>{{$locationItem->location_name}}</option>
                    @endforeach
                    @endif
                  </select>
                  </div>
                </div>
               </div>               
            </div>
           
            <div class="row">
              <div class="col-md-3">
              <div class="form-group" style="margin-right: 5px">
                <label for="exampleInputEmail1">{{ trans('message.purchase_report.search_type') }}</label>
                <div class="input-group">
                <select class="form-control select2" name="searchType" id="searchType" required>
                <option value="daily" <?= ($searchType=='daily') ? 'selected' : ''?>>Daily</option>
                <option value="monthly" <?= ($searchType=='monthly') ? 'selected' : ''?>>Monthly</option>
                <option value="yearly" <?= ($searchType=='yearly') ? 'selected' : ''?>>Yearly</option>
                <option value="custom" <?= ($searchType=='custom') ? 'selected' : ''?>>Custom</option>
                </select>
                </div>
                </div>
              </div>              

              <div class="col-md-3 dateField" style="display: none;">
              <div class="form-group" style="margin-right: 5px">
                  <label for="exampleInputEmail1">{{ trans('message.report.from') }}</label>
                  <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input class="form-control" id="from" type="text" name="from" value="<?= isset($from) ? $from : '' ?>">
                  </div>
              </div>
              </div>
              
              <div class="col-md-3 dateField" style="display: none;">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.report.to') }}</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control" id="to" type="text" name="to" value="<?= isset($to) ? $to : '' ?>">
                  </div>
                  </div>
              </div>

              <div class="col-md-3 yearField" style="display: none;">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.purchase_report.year') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="year" id="year" style="width:'100%' ">
                  <option value="all" <?= ($year=='all') ? 'selected' : ''?>>All</option>
                  @if(!empty($yearList))
                  @foreach($yearList as $res)
                  <option value="{{$res->year}}" <?= ($res->year == $year) ? 'selected' : '' ?>>{{$res->year}}</option>
                  @endforeach
                  @endif
                  </select>
                  </div>
                  </div>
              </div>

              <?php
              $monthList = array(
                '01'=>'January',
                '02'=>'February',
                '03'=>'March',
                '04'=>'April',
                '05'=>'May',
                '06'=>'June',
                '07'=>'July',
                '08'=>'August',
                '09'=>'September',
                '10'=>'October',
                '11'=>'November',
                '12'=>'December'
                );
              ?>

              <div class="col-md-3 monthField" style="display: none;">
              <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('message.purchase_report.month') }}</label>
                  <div class="input-group">
                  <select class="form-control select2" name="month" id="month">
                    <option value="all" <?= ($month == 'all') ? 'selected' : '' ?>>All</option>
                    @foreach($monthList as $key=>$val)
                    <option value="{{$key}}" <?= ($month == $key) ? 'selected' : '' ?>>{{$val}}</option>
                    @endforeach
                  </select>
                  </div>
                </div>
              </div>

             </div>
             <div class="row">
             <div class="col-md-1">
             <div class="form-group">
                <div class="input-group">
                <button type="submit" name="btn" class="btn btn-primary btn-flat">{{ trans('message.extra_text.filter') }}</button>
                </div>
              </div>
              </div>
             </div>
          </div>
          </form>
          <div class="col-md-3 col-xs-12">
            <br>
            <div class="btn-group pull-right">
              <a href="{{URL::to('/')}}/report/sales-report-csv" title="CSV" class="btn btn-default btn-flat" id="csv">{{ trans('message.extra_text.csv') }}</a>
              <a href="{{URL::to('/')}}/report/sales-report-pdf" title="PDF" class="btn btn-default btn-flat" id="pdf">{{ trans('message.extra_text.pdf') }}</a>
            </div>

          </div>

        </div>
        <br>
      </div><!--Top Box End-->

          <?php
            $qty = 0;
            $sales = 0;
            $cost = 0;
            $order = 0;
            $profit = 0;
          ?>
            @foreach ($itemList as $item)
            <?php
            $order += $item->total_order;
            $qty += $item->qty;
            $sales += $item->sale;
            $cost += $item->purchase;
            $profit = $sales-$cost;
            ?>
            @endforeach

      <div class="box">
        <div class="box-body">
          <div class="col-md-2 col-xs-6 border-right text-center">
              <h3 class="bold">{{number_format($order,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.no_of_orders') }}</span>
          </div>
          <div class="col-md-2 col-xs-6 border-right text-center">
              <h3 class="bold">{{ number_format($qty,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.sales_volume') }}</span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-center">
              <h3 class="bold">{{ Session::get('currency_symbol').number_format($sales ,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.sales_value') }} </span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-center">
              <h3 class="bold">{{Session::get('currency_symbol').number_format($cost,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.cost') }}</span>
          </div>
          <div class="col-md-2 col-xs-6 text-center">
              <h3 class="bold">
              <?php
                if($profit<0){
                echo Session::get('currency_symbol').number_format(abs($profit),2,'.',',');
                }
                else{
                echo Session::get('currency_symbol').number_format(abs($profit),2,'.',',');
                }
                ?>
              </h3>
              @if($profit<0)
              <span class="text-info">{{ trans('message.report.profit') }}</span>
              @else
              <span class="text-info">{{ trans('message.report.profit') }}</span>
              @endif
          </div> 
        </div>
      </div><!--Top Box End-->

      <div class="box">
        <div class="box-body">
            <div id="container">
              
            </div>
        </div>
      </div>
      
      <!-- Default box -->
      <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
             @if($searchType=='daily' || $searchType=='custom')
              <table id="salesList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">
                  {{ trans('message.report.date') }}
                  </th>
                  <th class="text-center">{{ trans('message.report.no_of_orders') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_volume') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_value') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.cost') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit_margin') }}(%)</th>    
                </tr>
                </thead>
                <tbody>
                <?php
                  $qty = 0;
                  $sales = 0;
                  $cost = 0;
                  $order = 0;
                ?>
                @foreach ($itemList as $item)

                <tr>
                  <td class="text-center"><a href="{{URL::to('/')}}/report/sales-report-by-date/{{ strtotime($item->ord_date) }}">{{ formatDate($item->ord_date) }}</a></td>
                  <td class="text-center">{{ $item->total_order }}</td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-center">{{ number_format(($item->sale),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->purchase),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->sale-$item->purchase),2,'.',',') }}</td>
                  <td class="text-center">
                  @if($item->purchase<=0)
                  {{number_format(100,2)}}
                  @else
                  {{number_format((($item->sale-$item->purchase))*100/$item->purchase,2)}}
                  @endif
                  </td>
                </tr>
               @endforeach
               
                </tfoot>
              </table>
             @elseif($searchType=='monthly')
              <table id="salesList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">
                  {{ trans('message.purchase_report.month') }}
                  </th>
                  <th class="text-center">{{ trans('message.report.no_of_orders') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_volume') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_value') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.cost') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit_margin') }}(%)</th>    
                </tr>
                </thead>
                <tbody>
                <?php
                  $qty = 0;
                  $sales = 0;
                  $cost = 0;
                  $order = 0;
                ?>
                @foreach ($itemList as $item)

                <tr>
                  <td class="text-center"><a class="monthClass" href="#" data-month="{{ date('m',strtotime($item->ord_date)) }}" data-year="{{ date('Y',strtotime($item->ord_date)) }}">{{ date('F-Y',strtotime($item->ord_date)) }}</a></td>
                  <td class="text-center">{{ $item->total_order }}</td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-center">{{ number_format(($item->sale),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->purchase),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->sale-$item->purchase),2,'.',',') }}</td>
                  <td class="text-center">
                  @if($item->purchase<=0)
                  {{number_format(100,2)}}
                  @else
                  {{number_format((($item->sale-$item->purchase))*100/$item->purchase,2)}}
                  @endif
                  </td>
                </tr>
               @endforeach
               
                </tfoot>
              </table>

             @elseif($searchType == 'yearly' && $year =='all')

              <table id="salesList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">
                  {{ trans('message.purchase_report.year') }}
                  </th>
                  <th class="text-center">{{ trans('message.report.no_of_orders') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_volume') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_value') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.cost') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit_margin') }}(%)</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $qty = 0;
                  $sales = 0;
                  $cost = 0;
                  $order = 0;
                ?>
                @foreach ($itemList as $item)

                <tr>
                  <td class="text-center"><a class="yearClass" href="#" data-year="{{ date('Y',strtotime($item->ord_date)) }}">{{ date('Y',strtotime($item->ord_date)) }}</a></td>
                  <td class="text-center">{{ $item->total_order }}</td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-center">{{ number_format(($item->sale),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->purchase),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->sale-$item->purchase),2,'.',',') }}</td>
                  <td class="text-center">
                  @if($item->purchase<=0)
                  {{number_format(100,2)}}
                  @else
                  {{number_format((($item->sale-$item->purchase))*100/$item->purchase,2)}}
                  @endif
                  </td>
                </tr>
               @endforeach
               
                </tfoot>
              </table>
             @elseif($searchType == 'yearly' && $year !='all' && $month == 'all')
              <table id="salesList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">
                  {{ trans('message.purchase_report.month') }}
                  </th>
                  <th class="text-center">{{ trans('message.report.no_of_orders') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_volume') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_value') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.cost') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit_margin') }}(%)</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $qty = 0;
                  $sales = 0;
                  $cost = 0;
                  $order = 0;
                ?>
                @foreach ($itemList as $item)

                <tr>
                  <td class="text-center"><a class="monthClass" href="#" data-month="{{ date('m',strtotime($item->ord_date)) }}" data-year="{{ date('Y',strtotime($item->ord_date)) }}">{{ date('F-Y',strtotime($item->ord_date)) }}</a></td>
                  <td class="text-center">{{ $item->total_order }}</td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-center">{{ number_format(($item->sale),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->purchase),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->sale-$item->purchase),2,'.',',') }}</td>
                  <td class="text-center">
                  @if($item->purchase<=0)
                  {{number_format(100,2)}}
                  @else
                  {{number_format((($item->sale-$item->purchase))*100/$item->purchase,2)}}
                  @endif
                  </td>
                </tr>
               @endforeach
               
                </tfoot>
              </table>

             @elseif($searchType == 'yearly' && $year !='all' && $month != 'all')

              <table id="salesList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">
                  {{ trans('message.form.date') }}
                  </th>
                  <th class="text-center">{{ trans('message.report.no_of_orders') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_volume') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_value') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.cost') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit_margin') }}(%)</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $qty = 0;
                  $sales = 0;
                  $cost = 0;
                  $order = 0;
                ?>
                @foreach ($itemList as $item)

                <tr>
                  <td class="text-center"><a href="{{URL::to('/')}}/report/sales-report-by-date/{{ strtotime($item->ord_date) }}">{{ formatDate($item->ord_date) }}</a></td>
                  <td class="text-center">{{ $item->total_order }}</td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-center">{{ number_format(($item->sale),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->purchase),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->sale-$item->purchase),2,'.',',') }}</td>
                  <td class="text-center">
                  @if($item->purchase<=0)
                  {{number_format(100,2)}}
                  @else
                  {{number_format((($item->sale-$item->purchase))*100/$item->purchase,2)}}
                  @endif
                  </td>
                </tr>
               @endforeach
               
                </tfoot>
              </table>

             @endif
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
    $("#salesList").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 3,
        "orderable": true
        } ],

        "language": '{{Session::get('dflt_lang')}}',
        "pageLength": '{{Session::get('row_per_page')}}'
    });

    $(".select2").select2({});
    
    $('#from').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: '{{Session::get('date_format_type')}}'
    });

    $('#to').datepicker({
        autoclose: true,
        todayHighlight: true,
        
        format: '{{Session::get('date_format_type')}}'
    });

    $(document).ready(function(){

      $('.dateField').hide();
      $('.yearField').hide();
      $('.monthField').hide();

      var type = $('#searchType').val();
      var year = $('#year').val();
      var month = $('#month').val();

      if(type == 'custom'){
        
        $('.dateField').show();
        $('.yearField').hide();
        $('.monthField').hide();
       
      }else if(type == 'yearly'){
        $('.yearField').show();
        $('.dateField').hide();
          if(year != 'all'){
            $('.monthField').show();
          }
      
        }

    });


    $("#searchType").on('change', function(){
      var type = $(this).val();
      if(type=='custom'){

        $('.dateField').show();
        $('.yearField').hide();
        $(".monthField").hide();

          $('#purchaseReport').validate({
              rules: {
                  from: {
                      required: true
                  },
                  to: {
                      required: true
                  }                     
              }
          });

      }else if( type != 'custom' && type == 'yearly' ){
        $('.dateField').hide();
        $('.yearField').show();
        $('#year option:selected').removeAttr('selected');
        $('#year').val('all').change();

        }else if(type != 'custom' && type != 'yearly'){
          $('.dateField').hide();
           $('.yearField').hide();
           $(".monthField").hide();
        }
    });

    // Month list showing
    $("#year").on('change', function(){
      var year = $(this).val();
      $('#month').val('all').change();
      if(year != 'all'){
        $(".monthField").show();
      }else{
        $(".monthField").hide();
      }
    });



   $('#pdf').on('click', function(event){
      event.preventDefault();
      var product = $('#product').val();
      var customer = $('#customer').val();
      var location = $('#location').val();

      var to = $('#to').val();
      var from = $('#from').val();
      var searchType = $("#searchType").val();

      if( searchType == 'custom' ){
        window.location = SITE_URL+"/report/sales-report-pdf?to="+to+"&from="+from+"&searchType="+searchType+"&product="+product+"&customer="+customer+"&location="+location;
      }else if(searchType !='yearly'){
        window.location = SITE_URL+"/report/sales-report-pdf?searchType="+searchType+"&product="+product+"&customer="+customer+"&location="+location;
      }else if(searchType =='yearly'){
      var year = $("#year").val();
      var month = $("#month").val();
        window.location = SITE_URL+"/report/sales-report-pdf?searchType="+searchType+'&year='+year+'&month='+month+"&product="+product+"&customer="+customer+"&location="+location;
      }

    });

   $('#csv').on('click', function(event){
     event.preventDefault();
      var product = $('#product').val();
      var customer = $('#customer').val();
      var location = $('#location').val();

      var to = $('#to').val();
      var from = $('#from').val();
      var searchType = $("#searchType").val();
     // alert(searchType);
      if( searchType == 'custom' ){
        window.location = SITE_URL+"/report/sales-report-csv?to="+to+"&from="+from+"&searchType="+searchType+"&product="+product+"&customer="+customer+"&location="+location;
      }else if(searchType !='yearly'){
        window.location = SITE_URL+"/report/sales-report-csv?searchType="+searchType+"&product="+product+"&customer="+customer+"&location="+location;
      }else if(searchType =='yearly'){
      var year = $("#year").val();
      var month = $("#month").val();
        window.location = SITE_URL+"/report/sales-report-csv?searchType="+searchType+'&year='+year+'&month='+month+"&product="+product+"&customer="+customer+"&location="+location;
      }

    });
  });

  // Search By yearly.By clicked on year and Month
  $(document).ready(function(){

    $( '#salesList' ).on( 'click', 'a.yearClass', function () {
      var year = $(this).attr('data-year');
      var product = $('#product').val();
      var customer = $('#customer').val();
      var location = $('#location').val();

      window.location = SITE_URL+"/report/sales-report?searchType=yearly&from=&to=&year="+year+"&month=all&product="+product+"&customer="+customer+"&location="+location+"&btn=";
     });

    $( '#salesList' ).on( 'click', 'a.monthClass', function () {
      var month = $(this).attr('data-month');
      var year = $(this).attr('data-year');
      
      var product = $('#product').val();
      var customer = $('#customer').val();
      var location = $('#location').val();


      window.location = SITE_URL+"/report/sales-report?searchType=yearly&from=&to=&year="+year+"&month="+month+"&product="+product+"&customer="+customer+"&location="+location+"&btn=";
     });
  });

// Graph Start
Highcharts.chart('container', {
    title: {
        text: 'Sales report',
        x: -20 //center
    },
    subtitle: {
        text: '',
        x: -20
    },
    xAxis: {
        categories: jQuery.parseJSON('{!! $date !!}')
    },
    yAxis: {
        title: {
            text: ''
        },
        plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
        }]
    },
    tooltip: {
        valueSuffix: ''
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
    },
    series: [{
        name: 'Sales Value',
        data: {{$sale}}
    }, {
        name: 'Cost Value',
        data: {{$purchase}}
    },{
        name: 'Profit',
        data: {{$profit_amount}}
    }]
});

    </script>
@endsection