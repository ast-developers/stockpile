@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <h4>{{ trans('message.customer_panel.my_payment')}}</h4>
            </div>
        </div>

      <!-- Default box -->
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>{{ trans('message.invoice.payment_no') }}</th>
                    <th>{{ trans('message.table.order_no') }}</th>
                    <th>{{ trans('message.invoice.invoice_no') }}</th>
                    
                    <th>{{ trans('message.invoice.payment_on') }}</th>
                    <th>{{ trans('message.invoice.amount') }}({{$currency->symbol}})</th>
                    <th>{{ trans('message.invoice.payment_date') }}</th>

                  </tr>
                  </thead>
                  <tbody>
                  @foreach($paymentList as $data)
                  <tr>
                    <td><a href="{{ url("customer-panel/view-receipt/$data->id") }}">{{sprintf("%04d", $data->id)}}</a></td>
                    <td><a href="{{ url("customer-panel/view-order-details/$data->order_id") }}">{{$data->order_reference}}</a></td>
                    <td><a href="{{ url("customer-panel/view-detail-invoice/$data->order_id/$data->invoice_id") }}">{{ $data->invoice_reference }}</a></td>
                   
                    <td>{{ $data->pay_type }}</td>
                    <td>{{ number_format($data->amount,2,'.',',') }}</td>
                    <td>{{formatDate($data->payment_date)}}</td>
                    
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