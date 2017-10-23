@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
           <div class="top-bar-title padding-bottom">{{ trans('message.extra_text.payments') }}</div>
          </div> 
        </div>
      </div>
    </div>
      <div class="box">
        <div class="box-body">
                <ul class="nav nav-tabs cus" role="tablist">
                <li class="active">
                 <a href='{{url("payment/list")}}' >{{ trans('message.extra_text.all') }}</a>
                 </li>
                 <li>
                 <a href="{{url("payment/filtering")}}" >{{ trans('message.extra_text.filter') }}</a>
                 </li>
               </ul>
        </div>
      </div>
      <!--Filtering Box End-->
      <!-- Default box -->
      <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="paymentList" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ trans('message.invoice.payment_no') }}</th>
                    <th>{{ trans('message.table.order_no') }}</th>
                    <th>{{ trans('message.invoice.invoice_no') }}</th>
                    <th>{{ trans('message.invoice.customer_name') }}</th>
                    <th>{{ trans('message.extra_text.payment_method') }}</th>
                    <th>{{ trans('message.invoice.amount') }}</th>
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
                    <td><a href="{{url("customer/edit/$data->customer_id")}}">{{ $data->name }}</a></td>
                    <td>{{ $data->pay_type }}</td>
                    <td>{{ Session::get('currency_symbol').number_format($data->amount,2,'.',',') }}</td>
                    <td>{{formatDate($data->payment_date)}}</td>
                    <td>
                    @if(!empty(Session::get('payment_edit')))
                        <a  title="View" class="btn btn-xs btn-primary" href='{{ url("payment/view-receipt/$data->id") }}'><span class="fa fa-eye"></span></a> &nbsp;
                    @endif
                    @if(!empty(Session::get('payment_delete')))
                        <form method="POST" action="{{ url("payment/delete") }}" accept-charset="UTF-8" style="display:inline">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{ $data->id }}">
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
    $("#paymentList").DataTable({
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