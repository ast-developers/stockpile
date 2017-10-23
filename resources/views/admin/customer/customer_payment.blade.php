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
                    <li>
                      <a href="{{url("customer/invoice/$customerData->debtor_no")}}" >{{ trans('message.extra_text.invoices') }}</a>
                    </li>
                    <li class="active">
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
                    <th>{{ trans('message.invoice.payment_no') }}</th>
                    <th>{{ trans('message.table.order_no') }}</th>
                    <th>{{ trans('message.invoice.invoice_no') }}</th>
                    <th>{{ trans('message.invoice.payment_on') }}</th>
                    <th>{{ trans('message.invoice.amount') }}({{Session::get('currency_symbol')}})</th>
                    <th>{{ trans('message.invoice.payment_date') }}</th>
                    <th>{{ trans('message.invoice.action') }}</th>
                  </tr>

                  </thead>
                  <tbody>
                  @foreach($paymentList as $data)
                  <tr>
                    <td><a href="{{ url("payment/view-receipt/$data->id") }}">{{sprintf("%04d", $data->id)}}</a></td>
                    <td><a href="{{ url("order/view-order-details/$data->order_id") }}">{{$data->order_reference}}</a></td>
                    <td><a href="{{ url("invoice/view-detail-invoice/$data->order_id/$data->invoice_id") }}">{{ $data->invoice_reference }}</a></td>
                    <td>{{ $data->pay_type }}</td>
                    <td>{{number_format($data->amount,2,'.',',') }}</td>
                    <td>{{formatDate($data->payment_date)}}</td>
                    <td>
                    
                    <a  title="View" class="btn btn-xs btn-primary" href='{{ url("payment/view-receipt/$data->id") }}'><span class="fa fa-eye"></span></a> &nbsp;
                   
                    @if(!empty(Session::get('payment_delete')))
                        <form method="POST" action="{{ url("customer/delete-sales-info") }}" accept-charset="UTF-8" style="display:inline">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action_name" value="delete_payment">
                            <input type="hidden" name="payment_no" value="{{$data->id}}">
                            <input type="hidden" name="customer_id" value="{{$customerData->debtor_no}}">
                            <button title="delete" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ trans('message.invoice.delete_payment_header') }}" data-message="{{ trans('message.invoice.delete_payment') }}">
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
          "targets": 6,
          "orderable": false
          } ],

          "language": '{{Session::get('dflt_lang')}}',
          "pageLength": '{{Session::get('row_per_page')}}'
      });
      
    });

    </script>
@endsection