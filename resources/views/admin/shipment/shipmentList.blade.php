@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">

    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
           <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.shipments') }}</div>
          </div> 
        </div>
      </div>
    </div>
      <div class="box">
        <div class="box-body">
                <ul class="nav nav-tabs cus" role="tablist">
                <li class="active">
                 <a href='{{url("shipment/list")}}' >{{ trans('message.extra_text.all') }}</a>
                 </li>
                 <li>
                 <a href="{{url("shipment/filtering")}}" >{{ trans('message.extra_text.filter') }}</a>
                 </li>
               </ul>
        </div>
      </div>
      <!-- Default box -->
      <div class="box">
            <!-- /.box-header -->
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <div class="box-body">
              <table id="shipmentList" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="5%" class="text-center">{{ trans('message.invoice.shipment_no') }}</th>
                  <th width="10%" class="text-center">{{ trans('message.invoice.order_no') }}</th>
                  <th>{{ trans('message.invoice.customer_name') }}</th>
                  <th class="text-center">{{ trans('message.extra_text.quantity') }}</th>
                  <th class="text-center">{{ trans('message.invoice.status') }}</th>
                  <th width="15%" class="text-center">{{ trans('message.invoice.packed_date') }}</th>
                  <th width="10%" align="center">{{ trans('message.invoice.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shipmentList as $key=>$data)
                <tr>
                  <td align="center"><a href="{{ url('shipment/view-details/'.$data->order_id.'/'.$data->shipment_id) }}">{{sprintf("%04d", $data->shipment_id)}}</a></td>
                  <td align="center"><a href="{{URL::to('/')}}/order/view-order-details/{{$data->order_id}}">{{ $data->reference }}</a></td>
                  <td><a href="{{url("customer/edit/$data->debtor_no")}}">{{ $data->name }}</a></td>
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
                  @if(!empty(Session::get('shipment_edit')))
                    @if($data->status == 0)
                    <a class="btn btn-xs btn-info shipment_status" data-id="{{$data->shipment_id}}"><span class="fa fa-truck"></span></a> &nbsp;
                    @endif
                    <a class="btn btn-xs btn-primary" href='{{ url("shipment/edit/$data->shipment_id") }}'><span class="fa fa-edit"></span></a> &nbsp;
                  @endif
                  @if(!empty(Session::get('shipment_delete')))  
                    <form method="POST" action="{{ url("shipment/delete/$data->shipment_id") }}" accept-charset="UTF-8" style="display:inline">
                        {!! csrf_field() !!}
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
      <!-- /.box -->

    </section>

@include('layouts.includes.message_boxes')

@endsection

@section('js')
    <script type="text/javascript">

  $(function () {
    $("#shipmentList").DataTable({
      "order": [],
      "columnDefs": [ {
        "targets": 5,
        "orderable": true
        } ],

        "language": '{{Session::get('dflt_lang')}}',
        "pageLength": '{{Session::get('row_per_page')}}'
    });
    
  });

  $(document).ready(function(){
      $('.shipment_status').on('click',function(event){
      event.preventDefault();
       var id = $(this).attr('data-id');
       var token = $("#token").val();
      
       $.ajax({
        method:"POST",
        url   :SITE_URL+"/shipment/status-change",
        data  :{"id":id,"_token":token}
       }).done(function(result){
        var status = result.status_no;
        if(status==1){
          $("#colId-"+id).html("Delivered");
          $("#colId-"+id).removeClass("label-warning");
          $("#colId-"+id).addClass("label-success");
        }
       });
    });
  });

    </script>
@endsection