@extends('layouts.app')


@section('content')

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
        
        <div class="box">
           <div class="panel-body">
                <ul class="nav nav-tabs cus" role="tablist">
                    <li <?=isset($sub_menu) && $sub_menu == 'profile' ? ' class="active"' : ''?>>
                      <a href='{{url("customer/edit/$customerData->debtor_no")}}' >{{ trans('message.sidebar.profile') }}</a>
                    </li>
                    
                    <li <?=isset($sub_menu) && $sub_menu == 'sale-order' ? ' class="active"' : ''?>>
                      <a href="{{url("customer/order/$customerData->debtor_no")}}" >{{ trans('message.extra_text.sales_orders') }}</a>
                    </li>
                    <li <?=isset($sub_menu) && $sub_menu == 'invoice' ? ' class="active"' : ''?>>
                      <a href="{{url("customer/invoice/$customerData->debtor_no")}}" >{{ trans('message.extra_text.invoices') }}</a>
                    </li>
                    <li <?=isset($sub_menu) && $sub_menu == 'payment' ? ' class="active"' : ''?>>
                      <a href="{{url("customer/payment/$customerData->debtor_no")}}" >{{ trans('message.extra_text.payments') }}</a>
                    </li>
                    <li <?=isset($sub_menu) && $sub_menu == 'delivery' ? ' class="active"' : ''?>>
                      <a href="{{url("customer/shipment/$customerData->debtor_no")}}" >{{ trans('message.extra_text.deliveries') }}</a>
                    </li>
               </ul>
              <div class="clearfix"></div>
           </div>
        </div> 
        <h3>{{$customerData->name}}</h3> 
        
        <div class="box">
      
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="5%" class="text-center">{{ trans('message.invoice.shipment_no') }}</th>
                  <th width="15%" class="text-center">{{ trans('message.invoice.order_no') }}</th>
                  
                  <th class="text-center">{{ trans('message.extra_text.quantity') }}</th>
                  <th class="text-center">{{ trans('message.invoice.status') }}</th>
                  <th width="" class="text-center">{{ trans('message.invoice.packed_date') }}</th>
                  <th width="" align="center">{{ trans('message.invoice.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shipmentList as $key=>$data)
                <tr>
                  <td align="center"><a href="{{ url('shipment/view-details/'.$data->order_id.'/'.$data->shipment_id) }}">{{sprintf("%04d", $data->shipment_id)}}</a></td>
                  <td align="center"><a href="{{URL::to('/')}}/order/view-order-details/{{$data->order_id}}">{{ $data->reference }}</a></td>
                  
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
                  <td>
                    <a class="btn btn-xs btn-primary" href="{{ url('shipment/view-details/'.$data->order_id.'/'.$data->shipment_id) }}"><span class="fa fa-eye"></span></a> &nbsp;
                    @if(!empty(Session::get('shipment_edit')))
                    <a class="btn btn-xs btn-info" href='{{ url("shipment/edit/$data->shipment_id") }}'><span class="fa fa-edit"></span></a> &nbsp;
                    @endif
                     @if(!empty(Session::get('shipment_delete')))
                    <form method="POST" action="{{ url("customer/delete-sales-info") }}" accept-charset="UTF-8" style="display:inline">
                        {!! csrf_field() !!}
                        <input type="hidden" name="action_name" value="delete_shipment">
                        <input type="hidden" name="shipment_id" value="{{$data->shipment_id}}">
                        <input type="hidden" name="customer_id" value="{{$customerData->debtor_no}}">
                        <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_shipment') }}" data-message="{{ trans('message.invoice.delete_shipment_confirm') }}">
                            <i class="glyphicon glyphicon-trash"></i> 
                        </button>
                    </form>
                  @endif
                  </td>
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
        "order": [],
        "columnDefs": [ {
          "targets": 5,
          "orderable": false
          } ],

          "language": '{{Session::get('dflt_lang')}}',
          "pageLength": '{{Session::get('row_per_page')}}'
      });
      
    });

    </script>
@endsection