@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <!--Default box -->
        <div class="box">
           <div class="panel-body">
                <ul class="nav nav-tabs cus" role="tablist">
                    
                    <li>
                      <a href='{{url("customer/edit/$customerData->debtor_no")}}' >{{ trans('message.sidebar.profile') }}</a>
                    </li>
                    
                    <li class="active">
                      <a href="{{url("customer/order/$customerData->debtor_no")}}" >{{ trans('message.extra_text.sales_orders') }}</a>
                    </li>
                    <li>
                      <a href="{{url("customer/invoice/$customerData->debtor_no")}}" >{{ trans('message.extra_text.invoices') }}</a>
                    </li>
                    <li>
                      <a href="{{url("customer/payment/$customerData->debtor_no")}}" >{{ trans('message.extra_text.payments') }}</a>
                    </li>
                    <li>
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
                    <th>{{ trans('message.table.order') }} #</th>
                    <th>{{ trans('message.extra_text.quantity') }}</th>
                    <th>{{ trans('message.invoice.invoiced') }}</th>
                    <th>{{ trans('message.invoice.packed') }}</th>
                    <th>{{ trans('message.invoice.paid') }}</th>
                    <th>{{ trans('message.invoice.total') }}</th>
                    <th>{{ trans('message.table.ord_date') }}</th>
                    <th width="11%">{{ trans('message.table.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($salesOrderData as $data)
                <tr>
                  <td><a href="{{URL::to('/')}}/order/view-order-details/{{$data->order_no}}">{{$data->reference }}</a></td>
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

                    <td>{{ Session::get('currency_symbol').number_format($data->order_amount,2,'.',',') }}</td>
                    <td>{{formatDate($data->ord_date)}}</td>

                  
                  <td class="text-center">
                    @if(!empty(Session::get('order_edit')))
                        <a title="View" class="btn btn-xs btn-primary" href={{ url("order/view-order-details/$data->order_no") }}><span class="fa fa-eye"></span></a> &nbsp;
                        <a  title="Edit" class="btn btn-xs btn-info" href='{{ url("order/edit/$data->order_no") }}'><span class="fa fa-edit"></span></a> &nbsp;
                    @endif

                    @if(!empty(Session::get('order_delete')))
                        <form method="POST" action="{{ url("customer/delete-sales-info") }}" accept-charset="UTF-8" style="display:inline">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action_name" value="delete_order">
                            <input type="hidden" name="order_no" value="{{$data->order_no}}">
                            <input type="hidden" name="customer_id" value="{{$customerData->debtor_no}}">
                            <button title="delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_order') }}" data-message="{{ trans('message.invoice.delete_order_confirm') }}">
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
          "targets": 4,
          "orderable": false
          } ],

          "language": '{{Session::get('dflt_lang')}}',
          "pageLength": '{{Session::get('row_per_page')}}'
      });
      
    });

    </script>
@endsection