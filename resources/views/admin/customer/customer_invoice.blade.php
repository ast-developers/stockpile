@extends('layouts.app')
@section('content')

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
        
        <div class="box">
           <div class="panel-body">
                <ul class="nav nav-tabs cus" role="tablist">
                    <li>
                      <a href='{{url("customer/edit/$customerData->debtor_no")}}' >{{ trans('message.sidebar.profile') }}</a>
                    </li>
                    
                    <li>
                      <a href="{{url("customer/order/$customerData->debtor_no")}}" >{{ trans('message.extra_text.sales_orders') }}</a>
                    </li>
                    <li class="active">
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
                    <th>{{ trans('message.table.invoice') }}</th>
                    <th>{{ trans('message.table.order_no') }}</th>
                    <th>{{ trans('message.table.total_price') }}</th>
                    <th>{{ trans('message.table.paid_amount') }}</th>
                    <th>{{ trans('message.table.paid_status') }}</th>
                    <th>{{ trans('message.invoice.invoice_date') }}</th>
                    <th width="10%">{{ trans('message.table.action') }}</th>
                  </tr>

                </thead>
                <tbody>
                @foreach ($salesOrderData as $data)
                  <tr>
                    <td><a href="{{URL::to('/')}}/invoice/view-detail-invoice/{{$data->order_reference_id.'/'.$data->order_no}}">{{$data->reference }}</a></td>
                    <td><a href="{{URL::to('/')}}/order/view-order-details/{{$data->order_reference_id}}">{{ $data->order_reference }}</a></td>
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
                    <td>
      
                    @if(!empty(Session::get('sales_edit')))
                        <a  title="edit" class="btn btn-xs btn-primary" href='{{ url("sales/edit/$data->order_no") }}'><span class="fa fa-edit"></span></a> &nbsp;
                    @endif
                    @if(!empty(Session::get('sales_edit')))
                      <form method="POST" action="{{ url("customer/delete-sales-info") }}" accept-charset="UTF-8" style="display:inline">
                          {!! csrf_field() !!}
                          <input type="hidden" name="action_name" value="delete_invoice">
                          <input type="hidden" name="invoice_no" value="{{$data->order_no}}">
                          <input type="hidden" name="customer_id" value="{{$data->debtor_no}}">
                          <button title="delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_invoice') }}" data-message="{{ trans('message.invoice.delete_invoice_confirm') }}">
                          <i class="fa fa-remove" aria-hidden="true"></i>
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
          "targets": 3,
          "orderable": false
          } ],

          "language": '{{Session::get('dflt_lang')}}',
          "pageLength": '{{Session::get('row_per_page')}}'
      });
      
    });

    </script>
@endsection