@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-body">
        <div class="col-md-6">
          <div style="font-weight:bold;font-size:18px;padding-top:5px;">{{ trans('message.extra_text.sales_report_on') }} {{formatDate($reportDate)}}</div>
        </div>
          <div class="col-md-6">
            <div class="btn-group pull-right">
              <a href="{{URL::to('/')}}/report/sales-report-by-date-csv/{{$date}}" title="CSV" class="btn btn-default btn-flat">{{ trans('message.extra_text.csv') }}</a>
              <a href="{{URL::to('/')}}/report/sales-report-by-date-pdf/{{$date}}" title="PDF" class="btn btn-default btn-flat">{{ trans('message.extra_text.pdf') }}</a>
            </div>
          </div>
        </div>
      </div><!--Top Box End-->
      <?php
      $tax_total = 0;
      $qty_total = 0;
      $sales_total = 0;
      $cost_total = 0;
      $profit_total = 0;
      ?>
        @foreach ($itemList as $item)
        <?php
        $qty_total += $item->qty;
        $sales_total += $item->sales_price_total-$item->tax;
        $cost_total += $item->purch_price_amount;
        $tax_total += $item->tax;
        $profit_amount = ($item->sales_price_total-$item->tax-$item->purch_price_amount);
        $profit_total += $profit_amount;
        ?>
        @endforeach
      <div class="box">
        <div class="box-body">
          <div class="col-md-2 col-xs-6 border-right text-center">
              <h3 class="bold">{{ number_format($qty_total,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.extra_text.quantity') }}</span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-center">
              <h3 class="bold">{{ Session::get('currency_symbol').number_format($sales_total ,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.sales_value') }} </span>
          </div>
          <div class="col-md-3 col-xs-6 border-right text-center">
              <h3 class="bold">{{Session::get('currency_symbol').number_format($cost_total,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.cost') }}</span>
          </div>

          <div class="col-md-2 col-xs-6 border-right text-center">
              <h3 class="bold">{{number_format($tax_total,2,'.',',')}}</h3>
              <span class="text-info">{{ trans('message.report.tax') }}</span>
          </div>

          <div class="col-md-2 col-xs-6 text-center">
              <h3 class="bold">
                @if($profit_total<0)
                -{{Session::get('currency_symbol').number_format(abs($profit_total),2,'.',',')}}
                @else
               {{Session::get('currency_symbol').number_format(abs($profit_total),2,'.',',')}}
                @endif
              </h3>
              @if($profit_total<0)
              <span class="text-info">{{ trans('message.report.profit') }}</span>
              @else
              <span class="text-info">{{ trans('message.report.profit') }}</span>
              @endif
          </div> 
        </div>
      </div><!--Top Box End-->
      <!-- Default box -->
      <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="salesList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">{{ trans('message.report.order_no') }}</th>
                  <th class="text-center">{{ trans('message.report.customer') }}</th>                  
                  <th class="text-center">{{ trans('message.extra_text.quantity') }}</th>
                  <th class="text-center">{{ trans('message.report.sales_value') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.cost') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.tax') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit') }}({{Session::get('currency_symbol')}})</th>
                  <th class="text-center">{{ trans('message.report.profit_margin') }}(%)</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $qty = 0;
                  $sales_price = 0;
                  $purchase_price = 0;
                  $tax = 0;
                  $total_profit = 0;
                   
                ?>
                @foreach ($itemList as $item)
                <?php
                $profit = ($item->sales_price_total-$item->tax-$item->purch_price_amount);
                
                if($item->purch_price_amount<=0){
                  $profit_margin = 100;
                }else{
                $profit_margin = ($profit*100)/$item->purch_price_amount;
                }

                $qty += $item->qty;
                $sales_price += $item->sales_price_total;
                $purchase_price += $item->purch_price_amount;
                $tax += $item->tax;
                $total_profit += $profit 
                ?>
                <tr>
                  <td class="text-center"><a href="{{URL::to('/')}}/order/view-order-details/{{$item->order_reference_id}}">{{ $item->order_reference }}</a></td>
                  
                  <td class="text-center"><a href="{{URL::to('/')}}/customer/edit/{{$item->debtor_no}}">{{ $item->name }}</a></td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-center">{{ number_format(($item->sales_price_total-$item->tax),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->purch_price_amount),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($item->tax),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($profit),2,'.',',') }}</td>
                  <td class="text-center">{{ number_format(($profit_margin),2,'.',',') }}</td>
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
    $("#salesList").DataTable({
      "order": [],

      "columnDefs": [ {
        "targets": 7,
        "orderable": false
        } ],

        "language": '{{Session::get('dflt_lang')}}',
        "pageLength": '{{Session::get('row_per_page')}}'
    });
    
  });

    </script>
@endsection