@extends('layouts.customer_panel')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <h4>{{ trans('message.customer_panel.my_shipment')}}</h4>
            </div>
        </div>
      <!-- Default box -->
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="5%" class="text-center">{{ trans('message.invoice.shipment_no') }}</th>
                  <th width="10%" class="text-center">{{ trans('message.invoice.order_no') }}</th>
                  
                  <th class="text-center">{{ trans('message.invoice.shipment_qty') }}</th>
                  <th class="text-center">{{ trans('message.invoice.status') }}</th>
                  <th width="10%" class="text-center">{{ trans('message.invoice.packed_date') }}</th>
                  
                </tr>
                </thead>
                <tbody>
                @foreach($shipmentList as $key=>$data)
                <tr>
                  <td align="center"><a href="{{ url('customer-panel/view-shipment-details/'.$data->order_id.'/'.$data->shipment_id) }}">{{sprintf("%04d", $data->shipment_id)}}</a></td>
                  <td align="center"><a href="{{URL::to('/')}}/customer-panel/view-order-details/{{$data->order_id}}">{{ $data->reference }}</a></td>
                  
                  <td class="text-center">{{ $data->total_shipment }}</td>
                  <?php
                    if($data->status == 0){
                      $status = trans('message.invoice.shipment_packed');
                      $label = 'warning';
                    }else{
                      $status = trans('message.invoice.shipment_delivered');
                      $label = 'success';
                    }
                  ?>
                  <td class="text-center"><span class="label label-{{$label}}" id="colId-{{$data->shipment_id}}">{{ $status }}</span></td>
                  <td class="text-center">{{ formatDate($data->packed_date)}}</td>
                  
                </tr>
               @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        
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