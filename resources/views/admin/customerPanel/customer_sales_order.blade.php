@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <h4>{{ trans('message.customer_panel.my_order')}}</h4>
            </div>
        </div>
      <!-- Default box --> 
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="orderList" class="table table-bordered table-striped">
                <thead>
              
                  <tr>
                    <th>{{ trans('message.table.order') }}</th>
                    <th>{{ trans('message.invoice.ordered') }}</th>
                    <th>{{ trans('message.invoice.invoiced') }}</th>
                    <th>{{ trans('message.invoice.packed') }}</th>
                    <th>{{ trans('message.invoice.paid') }}</th>
                    <th>{{ trans('message.table.total') }}({{$currency->symbol}})</th>
                    <th>{{ trans('message.table.ord_date') }}</th>
                  </tr>
                
                </thead>
                <tbody>
                @foreach ($salesOrderData as $data)
                <tr>
                    <td> <a title="view" href="{{ url("customer-panel/view-order-details/$data->order_no") }}">{{$data->reference }}</a></td>
                    
                    <td>{{ $data->ordered_quantity }}</td>

                    @if( $data->invoiced_quantity == 0 )
                    <td><span class="fa fa-circle-thin"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->invoiced_quantity)== 0)
                    <td><span class="fa fa-circle"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->invoiced_quantity)>0)
                    <td><span class="glyphicon glyphicon-adjust"></span></td>
                    @endif


                    @if( $data->packed_qty == 0 )
                    <td><span class="fa fa-circle-thin"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->packed_qty)== 0)
                    <td><span class="fa fa-circle"></span></td>
                    @elseif(abs($data->ordered_quantity) - abs($data->packed_qty)>0)
                    <td><span class="glyphicon glyphicon-adjust"></span></td>
                    @endif

                    @if( $data->paid_amount == 0 )
                    <td><span class="fa fa-circle-thin"></span></td>
                    @elseif(abs($data->order_amount) - abs($data->paid_amount)== 0)
                    <td><span class="fa fa-circle"></span></td>
                    @elseif(abs($data->order_amount) - abs($data->paid_amount)>0)
                    <td><span class="glyphicon glyphicon-adjust"></span></td>
                    @endif

                    <td>{{ number_format($data->order_amount,2,'.',',') }}</td>
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
    $("#orderList").DataTable({
      "order": [],
    });
    
  });
    </script>
@endsection