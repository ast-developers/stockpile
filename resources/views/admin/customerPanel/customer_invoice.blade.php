@extends('layouts.customer_panel')


@section('content')

    <!-- Main content -->
    <section class="content">
      <!-- Default box --> 
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <h4>{{ trans('message.customer_panel.my_invoice')}}</h4>
            </div>
        </div>

        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>{{ trans('message.table.invoice') }}</th>
                    <th>{{ trans('message.table.order_no') }}</th>
                    
                    <th>{{ trans('message.table.total_price') }}({{$currency->symbol}})</th>
                    <th>{{ trans('message.table.paid_amount') }}({{$currency->symbol}})</th>
                    <th>{{ trans('message.table.paid_status') }}</th>
                    <th>{{ trans('message.invoice.invoice_date') }}</th>
                   
                  </tr>
                </thead>
                <tbody>
                @foreach ($salesOrderData as $data)
                  
                  <tr>
                    <td><a href="{{URL::to('/')}}/customer-panel/view-detail-invoice/{{$data->order_reference_id.'/'.$data->order_no}}">{{$data->reference }}</a></td>
                    <td><a href="{{URL::to('/')}}/customer-panel/view-order-details/{{$data->order_reference_id}}">{{ $data->order_reference }}</a></td>
                    
                    <td>{{ Session::get('currency_symbol').number_format($data->total,2,'.',',') }}</td>
                    <td>{{ Session::get('currency_symbol').number_format($data->paid_amount,2,'.',',') }}</td>
  
                    @if($data->paid_amount == 0)
                      <td><span class="label label-danger">{{ trans('message.invoice.unpaid')}}</span></td>
                    @elseif($data->paid_amount > 0 && $data->total > $data->paid_amount )
                      <td><span class="label label-warning">{{ trans('message.invoice.partially_paid')}}</span></td>
                    @elseif($data->paid_amount<=$data->paid_amount)
                      <td><span class="label label-success">{{ trans('message.invoice.paid')}}</span></td>
                    @endif

                    <td>{{formatDate($data->ord_date)}}</td>
                   
                  </tr>
                 @endforeach

                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        
        <!-- /.box-footer-->
    

    @include('layouts.includes.message_boxes')
    </section>
@endsection


@section('js')
    <script type="text/javascript">

    $(function () {
      
      
      $("#example1").DataTable({
        "order": []
      });
      
    });

    </script>
@endsection